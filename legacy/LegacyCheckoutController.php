<?php

declare(strict_types=1);

namespace Legacy;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * LegacyCheckoutController - VERSIÓN ORIGINAL MONOLÍTICA "ANTES DE REFACTORIZAR"
 * 
 * Este archivo representa el estado original del sistema de checkout de "Artesanías del Valle"
 * previo a la aplicación de la arquitectura limpia, principios SOLID y patrones de diseño GoF.
 * 
 * Contiene deliberadamente múltiples antipatrones, Code Smells documentados por Martin Fowler
 * y violaciones directas a los 5 principios SOLID para fines de diagnóstico y sustentación académica.
 * 
 * @package Legacy
 * @author Equipo Monolito Legacy (2022)
 */
class LegacyCheckoutController
{
    /**
     * SDK Externo instanciado directamente violando DIP (Línea 30)
     */
    private $stripeClient;
    private $legacyBankSdk;

    public function __construct()
    {
        // VIOLACIÓN DIP (Líneas 36-37): Acoplamiento directo a implementaciones concretas
        $this->stripeClient = new \stdClass(); // Simula new \Stripe\StripeClient('sk_test_123');
        $this->legacyBankSdk = new \stdClass(); // Simula new \ExternalLegacyBankGateway();
    }

    /**
     * CODE SMELL: Long Method / God Method (Líneas 43-178)
     * VIOLACIÓN SRP: Un solo método valida HTTP, calcula negocio, cobra en 4 pasarelas,
     * descuenta stock en crudo, formatea comprobantes, envía correos y audita en logs.
     */
    public function processCheckout(Request $request)
    {
        // 1. VALIDACIÓN MANUAL Y ACOPLADA (Líneas 48-60)
        $items = $request->input('items', []);
        $paymentMethod = $request->input('payment_method');
        $shippingType = $request->input('shipping_type', 'standard');
        $invoiceType = $request->input('invoice_type', 'ticket');
        $customerEmail = $request->input('customer_email');
        $wantsGiftWrap = (bool) $request->input('gift_wrap', false);
        $wantsInsurance = (bool) $request->input('artisan_insurance', false);

        if (empty($items) || !is_array($items)) {
            return response()->json(['error' => 'El carrito no contiene artesanías válidas.'], 400);
        }
        if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Correo de cliente inválido.'], 400);
        }

        // 2. CÁLCULO DE SUBTOTALES CON LÓGICA DE NEGOCIO ESPAGUETI (Líneas 62-81)
        $subtotal = 0.0;
        foreach ($items as $item) {
            // CODE SMELL: Feature Envy (Líneas 65-70) - Maneja directamente estructuras internas
            $qty = (int) ($item['quantity'] ?? 1);
            $unitPrice = (float) ($item['price'] ?? 0.0);
            if ($qty <= 0 || $unitPrice <= 0) {
                return response()->json(['error' => 'Precios o cantidades inconsistentes'], 422);
            }
            $subtotal += ($qty * $unitPrice);
        }

        // VIOLACIÓN OCP / CODE SMELL: Switch Statements / Cascada de Ifs para Envío (Líneas 74-81)
        $shippingCost = 0.0;
        if ($shippingType === 'standard') {
            $shippingCost = 12000.0; // Tarifa nacional terrestre
        } elseif ($shippingType === 'express') {
            $shippingCost = 25000.0; // Envío aéreo prioritario
        } elseif ($shippingType === 'artisan_pickup') {
            $shippingCost = 0.0;     // Recogida en taller de Ráquira o Tuchín
        } else {
            return response()->json(['error' => 'Tipo de envío desconocido'], 400);
        }

        // 3. LÓGICA CONDICIONAL PARA SERVICIOS ADICIONALES (DECORADORES MAL IMPLEMENTADOS) (Líneas 83-93)
        // CODE SMELL: Combinatorial Logic - Si agregamos más opciones, las variables y condiciones explotan
        $additionalCosts = 0.0;
        if ($wantsGiftWrap) {
            $additionalCosts += 15000.0; // Empaque ecológico en mimbre
        }
        if ($wantsInsurance) {
            // Seguro artesanal del 5% del valor sobre piezas frágiles de cerámica
            $additionalCosts += ($subtotal * 0.05);
        }

        $grandTotal = $subtotal + $shippingCost + $additionalCosts;

        // 4. PROCESAMIENTO DE PAGO CON CASCADA CONDICIONAL (Líneas 97-133)
        // VIOLACIÓN OCP & DIP / CODE SMELL: Switch Statement (Cascada if/else)
        // Para añadir un nuevo medio de pago (ej. Cripto o Efecty), este archivo DEBE ser modificado
        $paymentSuccess = false;
        $transactionId = null;
        $paymentResponseRaw = [];

        if ($paymentMethod === 'credit_card') {
            // Simulación de cobro con tarjeta
            $cardNumber = $request->input('card_number');
            if (empty($cardNumber) || strlen((string) $cardNumber) < 13) {
                return response()->json(['error' => 'Tarjeta inválida'], 422);
            }
            $transactionId = 'CC-' . uniqid();
            $paymentSuccess = true;
            $paymentResponseRaw = ['status' => 'authorized', 'auth_code' => '993821'];

        } elseif ($paymentMethod === 'pse') {
            // Simulación de pasarela PSE Colombia
            $bankCode = $request->input('bank_code');
            if (empty($bankCode)) {
                return response()->json(['error' => 'Debe seleccionar un banco PSE'], 422);
            }
            $transactionId = 'PSE-' . strtoupper(uniqid());
            $paymentSuccess = true;
            $paymentResponseRaw = ['status' => 'approved', 'bank' => $bankCode];

        } elseif ($paymentMethod === 'paypal') {
            // Simulación PayPal
            $paypalPayerId = $request->input('paypal_payer_id');
            $transactionId = 'PAYPAL-' . strtoupper(uniqid());
            $paymentSuccess = true;
            $paymentResponseRaw = ['status' => 'COMPLETED', 'payer' => $paypalPayerId];

        } elseif ($paymentMethod === 'legacy_bank') {
            // CODE SMELL: Inappropriate Intimacy & Acoplamiento Fuerte (Líneas 128-132)
            // Llama directamente un método incompatible de un SDK obsoleto sin adaptador
            $rawPayload = ['monto' => $grandTotal, 'divisa' => 'COP', 'ref' => 'REF-' . time()];
            // $res = $this->legacyBankSdk->execute_transaction_v2($rawPayload);
            $transactionId = 'LEGACY-TX-8831';
            $paymentSuccess = true;
            $paymentResponseRaw = ['COD_AUTH' => 'COD_AUT_200', 'MENSAJE' => 'APROBADA'];

        } else {
            return response()->json(['error' => 'Método de pago no soportado en la tienda'], 400);
        }

        if (!$paymentSuccess) {
            return response()->json(['error' => 'Transacción de pago rechazada por la pasarela'], 402);
        }

        // 5. EFECTOS SECUNDARIOS MEZCLADOS EN EL CONTROLADOR (Líneas 138-164)
        // VIOLACIÓN DIRECTA DE SRP: Persistencia, Inventario, Alertas y Auditoría aquí mismo

        // A. Descuento manual de Stock
        foreach ($items as $item) {
            // DB::table('products')->where('id', $item['id'])->decrement('stock', $item['quantity']);
            Log::info("LEGACY: Descontando stock de producto " . ($item['id'] ?? 0));
        }

        // B. Notificación artesano síncrona sin eventos (Líneas 147-151)
        // Si el servidor de correos falla, la orden completa del cliente crashea
        try {
            // Mail::raw("Alerta maestro artesano: Despachar pedido {$transactionId}", function($msg) {});
            Log::info("LEGACY: Enviando correo síncrono al maestro artesano de Tuchín / Ráquira");
        } catch (Exception $e) {
            Log::error("Fallo enviando alerta al artesano: " . $e->getMessage());
        }

        // C. Registro de auditoría monolítico (Línea 156)
        Log::channel('single')->info("LEGACY_AUDIT: Pedido {$transactionId} pagado por {$grandTotal} COP");

        // 6. GENERACIÓN DE COMPROBANTE CON IF/ELSE EN LUGAR DE FACTORÍA POLIMÓRFICA (Líneas 160-176)
        // VIOLACIÓN OCP / CODE SMELL: Hardcoded Document Generation
        $receiptOutput = '';
        if ($invoiceType === 'electronic') {
            // Simulación DIAN Factura Electrónica
            $cufe = hash('sha256', $transactionId . $grandTotal . date('Y-m-d'));
            $receiptOutput = "=== FACTURA ELECTRÓNICA DIAN (MODO LEGACY) ===\n";
            $receiptOutput .= "CUFE: {$cufe}\n";
            $receiptOutput .= "Cliente: {$customerEmail}\n";
            $receiptOutput .= "Total: \${$grandTotal} COP\n";
        } else {
            // Tirilla POS básica
            $receiptOutput = "=== TICKET POS ARTESANAL (MODO LEGACY) ===\n";
            $receiptOutput .= "Transacción: {$transactionId}\n";
            $receiptOutput .= "Total Pagado: \${$grandTotal} COP\n";
            $receiptOutput .= "¡Gracias por apoyar a los artesanos colombianos!\n";
        }

        // 7. RESPUESTA FINAL CON OBJETOS NO TIPADOS (Líneas 178-185)
        return response()->json([
            'status' => 'success',
            'transaction_id' => $transactionId,
            'subtotal' => $subtotal,
            'additional_costs' => $additionalCosts,
            'grand_total' => $grandTotal,
            'receipt' => $receiptOutput,
            'debug_notice' => 'Procesado con controlador legacy sin patrones GoF ni principios SOLID.'
        ], 200);
    }
}
