<?php

declare(strict_types=1);

namespace App\Patterns\Observer;

use App\Models\Order;

/**
 * Interfaz de Observador del patrón Observer (GoF).
 * Define el contrato unificado para todos los suscriptores a eventos del ciclo de vida del pedido.
 */
interface OrderObserverInterface
{
    /**
     * Reacciona a la notificación emitida por el sujeto observable.
     */
    public function update(Order $order, string $event): void;

    /**
     * Retorna el nombre descriptivo del observador para fines de auditoría y visualización.
     */
    public function getName(): string;
}
