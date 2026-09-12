<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;

/**
 * Objeto de Transferencia de Datos (DTO) inmutable con la respuesta completa del checkout.
 */
final class CheckoutResponseDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly ?Order $order,
        public readonly string $message,
        public readonly array $costBreakdown = [],
        public readonly ?array $receiptData = null,
        public readonly array $observerLogs = []
    ) {}
}
