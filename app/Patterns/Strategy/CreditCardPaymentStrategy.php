<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

/**
 * Estrategia Concreta 1: Cobro con Tarjeta de Crédito / Débito.
 */
class CreditCardPaymentStrategy implements PaymentStrategyInterface
{
    public function pay(float $amount, array $paymentDetails): PaymentResult
    {
        $cardNumber = (string) ($paymentDetails['card_number'] ?? '');
        $cvv = (string) ($paymentDetails['card_cvv'] ?? '');
        $holderName = (string) ($paymentDetails['card_holder'] ?? '');

        // Validación encapsulada dentro del algoritmo de la estrategia
        $cleanNumber = preg_replace('/\D/', '', $cardNumber);
        if (strlen($cleanNumber) < 13 || strlen($cleanNumber) > 19) {
            return PaymentResult::failed('Número de tarjeta inválido para procesamiento bancario.');
        }

        if (strlen($cvv) < 3 || strlen($cvv) > 4) {
            return PaymentResult::failed('Código de seguridad (CVV) inválido.');
        }

        // Simulación de transacción exitosa con pasarela de adquirencia
        $txId = 'CC-AUTH-' . strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 10));
        $authCode = str_pad((string) mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        return PaymentResult::success(
            transactionId: $txId,
            message: "Pago con tarjeta aprobado exitosamente a nombre de {$holderName}",
            payload: [
                'card_last4' => substr($cleanNumber, -4),
                'auth_code' => $authCode,
                'amount' => $amount,
                'currency' => 'COP',
                'franchise' => str_starts_with($cleanNumber, '4') ? 'VISA' : 'MASTERCARD',
            ]
        );
    }

    public function getMethodCode(): string
    {
        return 'credit_card';
    }

    public function getMethodName(): string
    {
        return 'Tarjeta de Crédito / Débito (Visa, Mastercard, AMEX)';
    }
}
