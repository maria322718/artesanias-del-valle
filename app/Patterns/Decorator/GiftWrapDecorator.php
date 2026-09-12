<?php

declare(strict_types=1);

namespace App\Patterns\Decorator;

/**
 * Decorador Concreto 1: Empaque Ecológico Artesanal en Mimbre o Fibra de Plátano.
 * Agrega un costo fijo de $15,000 COP y enriquece la descripción del pedido.
 */
class GiftWrapDecorator extends OrderCostDecorator
{
    public const GIFT_WRAP_FEE = 15000.0;

    public function calculateTotal(): float
    {
        return round($this->decoratedCost->calculateTotal() + self::GIFT_WRAP_FEE, 2);
    }

    public function getBreakdown(): array
    {
        $breakdown = $this->decoratedCost->getBreakdown();
        $breakdown[] = [
            'concept' => 'Empaque de Regalo Ecológico en Mimbre',
            'cost' => self::GIFT_WRAP_FEE,
            'description' => 'Canasto tejido a mano por artesanas del Valle en fibra natural biodegradable con lazo de fique.',
        ];

        return $breakdown;
    }
}
