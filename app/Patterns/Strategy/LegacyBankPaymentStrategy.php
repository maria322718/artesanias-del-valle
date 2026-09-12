<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

use App\Patterns\Adapter\LegacyBankAdapter;

/**
 * Estrategia Concreta que integra el Patrón Adapter:
 * Demuestra la sinergia arquitectónica entre el patrón Strategy (GoF) y el patrón Adapter (GoF).
 */
class LegacyBankPaymentStrategy implements PaymentStrategyInterface
{
    public function __construct(
        private readonly LegacyBankAdapter $bankAdapter
    ) {}

    public function pay(float $amount, array $paymentDetails): PaymentResult
    {
        $response = $this->bankAdapter->processPayment(
            amount: $amount,
            currency: 'COP',
            metadata: [
                'order_reference' => (string) ($paymentDetails['order_ref'] ?? ('ORD-' . uniqid())),
                'customer_email' => (string) ($paymentDetails['customer_email'] ?? 'artesano@valle.co'),
            ]
        );

        if (!$response->isSuccess()) {
            return PaymentResult::failed($response->getMessage(), $response->getRawPayload());
        }

        return PaymentResult::success(
            transactionId: $response->getTransactionId(),
            message: $response->getMessage(),
            payload: $response->getRawPayload()
        );
    }

    public function getMethodCode(): string
    {
        return 'legacy_bank';
    }

    public function getMethodName(): string
    {
        return 'Red Bancaria Tradicional (Integrada vía Patrón Adapter)';
    }
}
