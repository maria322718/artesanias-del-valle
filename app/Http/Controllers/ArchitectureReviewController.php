<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Product;
use App\Patterns\Adapter\ExternalLegacyBankGateway;
use App\Patterns\Adapter\LegacyBankAdapter;
use App\Patterns\Decorator\ArtisanInsuranceDecorator;
use App\Patterns\Decorator\BaseOrderCost;
use App\Patterns\Decorator\GiftWrapDecorator;
use App\Patterns\FactoryMethod\ElectronicInvoiceFactory;
use App\Patterns\FactoryMethod\SimpleTicketFactory;
use App\Patterns\Observer\Observers\ArtisanNotificationObserver;
use App\Patterns\Observer\Observers\AuditLogObserver;
use App\Patterns\Observer\Observers\StockReductionObserver;
use App\Patterns\Observer\OrderSubject;
use App\Patterns\Strategy\CreditCardPaymentStrategy;
use App\Patterns\Strategy\PaymentStrategyRegistry;
use App\Patterns\Strategy\PaypalPaymentStrategy;
use App\Patterns\Strategy\PSEPaymentStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArchitectureReviewController extends Controller
{
    public function index(): View
    {
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $auditLogs = AuditLog::latest()->take(10)->get();

        return view('architecture.index', compact('productsCount', 'ordersCount', 'auditLogs'));
    }

    /**
     * Endpoint API para ejecutar demostración interactiva de cualquiera de los 5 patrones GoF.
     */
    public function runPatternDemo(Request $request): JsonResponse
    {
        $pattern = (string) $request->input('pattern', 'strategy');

        return match ($pattern) {
            'strategy' => $this->demoStrategy(),
            'factory_method' => $this->demoFactoryMethod(),
            'observer' => $this->demoObserver(),
            'decorator' => $this->demoDecorator(),
            'adapter' => $this->demoAdapter(),
            default => response()->json(['error' => 'Patrón no reconocido'], 404),
        };
    }

    private function demoStrategy(): JsonResponse
    {
        $registry = new PaymentStrategyRegistry();
        $registry->register(new CreditCardPaymentStrategy());
        $registry->register(new PSEPaymentStrategy());
        $registry->register(new PaypalPaymentStrategy());

        $amount = 385000.0; // Sombrero Vueltiao

        $resCC = $registry->get('credit_card')->pay($amount, ['card_number' => '4532000011112222', 'card_cvv' => '998', 'card_holder' => 'María Camila Gómez']);
        $resPSE = $registry->get('pse')->pay($amount, ['pse_bank' => 'Bancolombia', 'doc_number' => '1020304050']);
        $resPP = $registry->get('paypal')->pay($amount, ['paypal_email' => 'turista@artesanias.org']);

        return response()->json([
            'pattern' => 'Strategy (Comportamiento)',
            'description' => 'Tres algoritmos intercambiables resueltos mediante PaymentStrategyRegistry sin ningún switch/case.',
            'results' => [
                'credit_card' => ['status' => $resCC->isSuccessful(), 'tx' => $resCC->getTransactionId(), 'msg' => $resCC->getMessage()],
                'pse' => ['status' => $resPSE->isSuccessful(), 'tx' => $resPSE->getTransactionId(), 'msg' => $resPSE->getMessage()],
                'paypal' => ['status' => $resPP->isSuccessful(), 'tx' => $resPP->getTransactionId(), 'msg' => $resPP->getMessage()],
            ],
            'lsp_check' => 'Todas las estrategias retornan la misma estructura PaymentResult garantizando el Principio de Sustitución de Liskov.',
        ]);
    }

    private function demoFactoryMethod(): JsonResponse
    {
        $dummyOrder = new Order([
            'id' => 101,
            'order_number' => 'ORD-VALLE-101',
            'customer_name' => 'Jurado Especialización',
            'customer_email' => 'profesor@evaluacion.edu.co',
            'subtotal' => 240000.0,
            'total_amount' => 240000.0,
            'payment_method' => 'PSE Bancolombia',
            'payment_reference' => 'PSE-TX-7712',
        ]);

        $electronicFactory = new ElectronicInvoiceFactory();
        $ticketFactory = new SimpleTicketFactory();

        $electronicDoc = $electronicFactory->generateDocument($dummyOrder);
        $ticketDoc = $ticketFactory->generateDocument($dummyOrder);

        return response()->json([
            'pattern' => 'Factory Method (Creacional)',
            'description' => 'Creadores concretos ElectronicInvoiceFactory y SimpleTicketFactory implementan createReceipt() sin condicionales.',
            'products' => [
                'electronic' => ['title' => $electronicDoc['title'], 'number' => $electronicDoc['document_number'], 'cufe' => $electronicDoc['metadata']['cufe'] ?? null],
                'ticket' => ['title' => $ticketDoc['title'], 'number' => $ticketDoc['document_number'], 'tipo' => $ticketDoc['metadata']['tipo'] ?? null],
            ],
        ]);
    }

    private function demoObserver(): JsonResponse
    {
        $subject = new OrderSubject();
        $auditObs = new AuditLogObserver();
        $artisanObs = new ArtisanNotificationObserver();
        $stockObs = new StockReductionObserver();

        $subject->attach($auditObs);
        $subject->attach($artisanObs);
        $subject->attach($stockObs);

        $dummyOrder = new Order([
            'id' => 999,
            'order_number' => 'ORD-DEMO-OBSERVER',
            'customer_name' => 'Comprador Cultural',
            'customer_email' => 'cultural@valle.co',
            'total_amount' => 420000.0,
            'payment_method' => 'Tarjeta Visa',
            'payment_reference' => 'CC-TEST-881',
        ]);

        $subject->notify($dummyOrder, 'order.completed');

        return response()->json([
            'pattern' => 'Observer (Comportamiento)',
            'description' => 'OrderSubject notificó en simultáneo a 3 observadores desacoplados (Auditoría, Notificación a Artesano e Inventario).',
            'execution_logs' => $subject->getExecutionLogs(),
        ]);
    }

    private function demoDecorator(): JsonResponse
    {
        $basePrice = 420000.0; // Vajilla Ráquira
        $base = new BaseOrderCost($basePrice);
        $withWrap = new GiftWrapDecorator($base);
        $withBoth = new ArtisanInsuranceDecorator($withWrap);

        return response()->json([
            'pattern' => 'Decorator (Estructural)',
            'description' => 'Composición dinámica sobre herencia. Se apilaron BaseOrderCost -> GiftWrapDecorator -> ArtisanInsuranceDecorator.',
            'layers' => [
                'base_only' => $base->calculateTotal(),
                'base_plus_wrap' => $withWrap->calculateTotal(),
                'base_plus_wrap_plus_insurance' => $withBoth->calculateTotal(),
            ],
            'breakdown' => $withBoth->getBreakdown(),
        ]);
    }

    private function demoAdapter(): JsonResponse
    {
        $incompatibleSdk = new ExternalLegacyBankGateway('BANCO-ANTIGUO-BOGOTA-1980');
        $adapter = new LegacyBankAdapter($incompatibleSdk);

        $response = $adapter->processPayment(310000.0, 'COP', ['order_reference' => 'ORD-TEST-ADAPTER']);

        return response()->json([
            'pattern' => 'Adapter (Estructural)',
            'description' => 'LegacyBankAdapter convirtió la llamada de processPayment(...) al método arcaico execute_transaction_v2(...).',
            'gateway_name' => $adapter->getGatewayName(),
            'target_success' => $response->isSuccess(),
            'transaction_id' => $response->getTransactionId(),
            'message' => $response->getMessage(),
            'adaptee_raw_result' => $response->getRawPayload(),
        ]);
    }
}
