<?php

declare(strict_types=1);

namespace App\Patterns\Observer\Observers;

use App\Models\Order;
use App\Patterns\Observer\OrderObserverInterface;
use Illuminate\Support\Facades\Log;

/**
 * Observador Concreto 2: Notificación y Despacho hacia los Maestros Artesanos.
 * Desacopla la comunicación con talleres tradicionales en Boyacá, Córdoba, Guajira, etc.
 */
class ArtisanNotificationObserver implements OrderObserverInterface
{
    /**
     * @var array<int, array{artisan: string, region: string, product: string, order_id: int, message: string}>
     */
    private array $dispatchedAlerts = [];

    public function update(Order $order, string $event): void
    {
        if ($event !== 'order.completed' && $event !== 'order.paid') {
            return;
        }

        // Simula la lectura de productos para notificar al maestro de cada región
        try {
            $items = $order->items ?? [];
        } catch (\Throwable) {
            $items = []; // Graceful fallback in unit tests without DB
        }

        foreach ($items as $item) {
            try {
                $product = $item->product ?? null;
            } catch (\Throwable) {
                $product = null;
            }
            $artisanName = $product?->artisan_name ?? 'Comunidad Artesanal Regional';
            $origin = $product?->origin_region ?? 'Colombia';
            $craftName = $product?->name ?? 'Artesanía Tradicional';

            $message = "Alerta SMS/WhatsApp para {$artisanName} ({$origin}): Preparar despacho de {$item->quantity} unidad(es) de '{$craftName}' para el pedido #{$order->id}.";

            try {
                Log::info("ARTESANO NOTIFICADO: {$message}");
            } catch (\Throwable) {
                // Skip in unit tests
            }

            $this->dispatchedAlerts[] = [
                'artisan' => $artisanName,
                'region' => $origin,
                'product' => $craftName,
                'order_id' => (int) $order->id,
                'message' => $message,
            ];
        }
    }

    public function getName(): string
    {
        return 'ArtisanNotificationObserver (Alerta de Despacho a Talleres Ancestrales)';
    }

    public function getDispatchedAlerts(): array
    {
        return $this->dispatchedAlerts;
    }
}
