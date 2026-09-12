<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

/**
 * Objeto de Valor Inmutable que encapsula el resultado de cualquier estrategia de pago.
 * Garantiza el Principio de Sustitución de Liskov (LSP) al unificar el contrato de salida.
 */
final class PaymentResult
{
    public function __construct(
        private readonly bool $successful,
        private readonly string $transactionId,
        private readonly string $message,
        private readonly array $rawPayload = []
    ) {}

    public static function success(string $transactionId, string $message = 'Pago procesado exitosamente', array $payload = []): self
    {
        return new self(true, $transactionId, $message, $payload);
    }

    public static function failed(string $message, array $payload = []): self
    {
        return new self(false, '', $message, $payload);
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
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
