<?php

declare(strict_types=1);

namespace App\Patterns\Decorator;

/**
 * Componente Concreto (Concrete Component) del patrón Decorator.
 * Representa el valor base de las artesanías seleccionadas en el carrito sin ningún servicio extra.
 */
class BaseOrderCost implements OrderCostInterface
{
    public function __construct(
        private readonly float $productsSubtotal
    ) {}

    public function calculateTotal(): float
    {
        return round($this->productsSubtotal, 2);
    }

    public function getBreakdown(): array
    {
        return [
            [
                'concept' => 'Subtotal Artesanías Colombianas',
                'cost' => round($this->productsSubtotal, 2),
                'description' => 'Valor comercial directo de las piezas artesanales adquiridas.',
            ],
        ];
    }
}
