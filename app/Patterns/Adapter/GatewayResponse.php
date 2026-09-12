<?php

declare(strict_types=1);

namespace App\Patterns\Adapter;

/**
 * Objeto de valor estandarizado que retorna cualquier pasarela que implementa PaymentGatewayInterface.
 */
final class GatewayResponse
{
    public function __construct(
        private readonly bool $isSuccess,
        private readonly string $transactionId,
        private readonly string $message,
        private readonly array $rawPayload = []
    ) {}

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRawPayload(): array
    {
        return $this->rawPayload;
    }
}
