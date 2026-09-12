<?php

declare(strict_types=1);

namespace App\Patterns\Decorator;

/**
 * Componente Base (Component) del patrón Decorator (GoF).
 * Define la interfaz unificada tanto para el costo base del pedido como para
 * los servicios artesanales adicionales agregados dinámicamente.
 */
interface OrderCostInterface
{
    /**
     * Calcula el monto total acumulado (base + decoraciones activas).
     */
    public function calculateTotal(): float;

    /**
     * Retorna el desglose detallado de todos los conceptos y capas de costo aplicados.
     * @return array<int, array{concept: string, cost: float, description: string}>
     */
    public function getBreakdown(): array;
}
