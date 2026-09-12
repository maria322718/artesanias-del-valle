<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Patterns\Decorator\ArtisanInsuranceDecorator;
use App\Patterns\Decorator\BaseOrderCost;
use App\Patterns\Decorator\GiftWrapDecorator;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('info', 'El carrito está vacío. Elige artesanías para continuar.');
        }

        $subtotal = 0.0;
        $hasFragile = false;

        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
            if (!empty($item['is_fragile'])) {
                $hasFragile = true;
            }
        }

        // Decorator pre-calculation for visual display
        $baseCost = new BaseOrderCost($subtotal);
        $giftWrapCost = GiftWrapDecorator::GIFT_WRAP_FEE;
        $insuranceCost = round(max(ArtisanInsuranceDecorator::MINIMUM_FEE, $subtotal * ArtisanInsuranceDecorator::INSURANCE_RATE), 2);

        $strategies = $this->checkoutService->getStrategyRegistry()->all();

        return view('checkout.index', compact('cart', 'subtotal', 'hasFragile', 'giftWrapCost', 'insuranceCost', 'strategies'));
    }

    public function process(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog.index')->with('error', 'El carrito expiró o está vacío.');
        }

        $customerData = [
            'name' => $request->input('customer_name', 'Amante del Arte Colombiano'),
            'email' => $request->input('customer_email', 'cliente@artesaniasdelvalle.co'),
            'phone' => $request->input('customer_phone', '3109876543'),
            'address' => $request->input('shipping_address', 'Av. Las Artesanías #45-12'),
            'shipping_type' => $request->input('shipping_type', 'standard'),
        ];

        $decoratorOptions = [
            'gift_wrap' => $request->boolean('gift_wrap'),
            'artisan_insurance' => $request->boolean('artisan_insurance'),
        ];

        $paymentMethod = (string) $request->input('payment_method', 'credit_card');
        $invoiceType = (string) $request->input('invoice_type', 'electronic');

        // Payment details gathered from request
        $paymentDetails = [
            'card_number' => $request->input('card_number', '4532111122223333'),
            'card_holder' => $request->input('card_holder', $customerData['name']),
            'card_cvv' => $request->input('card_cvv', '889'),
            'pse_bank' => $request->input('pse_bank', 'Bancolombia'),
            'doc_type' => $request->input('doc_type', 'CC'),
            'doc_number' => $request->input('doc_number', '1098765432'),
            'paypal_email' => $request->input('paypal_email', $customerData['email']),
            'order_ref' => 'REF-' . strtoupper(substr(uniqid(), 0, 8)),
        ];

        $responseDTO = $this->checkoutService->process(
            cartItems: $cart,
            customerData: $customerData,
            decoratorOptions: $decoratorOptions,
            paymentMethod: $paymentMethod,
            paymentDetails: $paymentDetails,
            invoiceType: $invoiceType
        );

        if (!$responseDTO->success) {
            return back()->withInput()->with('error', 'Error en el pago: ' . $responseDTO->message);
        }

        // Limpia el carrito tras compra exitosa
        $request->session()->forget('cart');

        // Almacena datos del resultado en flash session para la pantalla de confirmación
        $request->session()->put('last_checkout_result', [
            'order_id' => $responseDTO->order?->id,
            'order_number' => $responseDTO->order?->order_number,
            'customer_name' => $customerData['name'],
            'customer_email' => $customerData['email'],
            'total_amount' => $responseDTO->order?->total_amount,
            'payment_method' => $responseDTO->order?->payment_method,
            'payment_reference' => $responseDTO->order?->payment_reference,
            'cost_breakdown' => $responseDTO->costBreakdown,
            'receipt' => $responseDTO->receiptData,
            'observer_logs' => $responseDTO->observerLogs,
        ]);

        return redirect()->route('checkout.confirmation', ['order' => $responseDTO->order?->id ?? 1]);
    }

    public function confirmation(Request $request, int $orderId): View|RedirectResponse
    {
        $lastResult = $request->session()->get('last_checkout_result');
        $order = Order::with(['items.product', 'auditLogs'])->find($orderId);

        if (!$lastResult && !$order) {
            return redirect()->route('catalog.index')->with('info', 'No hay pedido reciente para mostrar.');
        }

        return view('checkout.confirmation', compact('lastResult', 'order'));
    }
}
