<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

/**
 * Estrategia Concreta 3: Pago Internacional con PayPal.
 */
class PaypalPaymentStrategy implements PaymentStrategyInterface
{
    public function pay(float $amount, array $paymentDetails): PaymentResult
    {
        $paypalEmail = (string) ($paymentDetails['paypal_email'] ?? '');

        if (empty($paypalEmail) || !filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
            return PaymentResult::failed('Correo de cuenta PayPal no válido o no autorizado.');
        }

        $captureId = 'PAYPAL-CAP-' . strtoupper(bin2hex(random_bytes(6)));

        return PaymentResult::success(
            transactionId: $captureId,
            message: "Transacción internacional aprobada en PayPal con cuenta {$paypalEmail}",
            payload: [
                'payer_email' => $paypalEmail,
                'intent' => 'CAPTURE',
                'amount_cop' => $amount,
                'status' => 'COMPLETED',
            ]
        );
    }

    public function getMethodCode(): string
    {
        return 'paypal';
    }

    public function getMethodName(): string
    {
        return 'PayPal Internacional (Compras desde el Exterior)';
    }
}
