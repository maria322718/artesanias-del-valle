<?php

declare(strict_types=1);

namespace App\Patterns\Decorator;

/**
 * Decorador Abstracto (Decorator) del patrón Decorator (GoF).
 * Mantiene una referencia al componente decorado e implementa OrderCostInterface,
 * delegando las operaciones al objeto envuelto.
 */
abstract class OrderCostDecorator implements OrderCostInterface
{
    public function __construct(
        protected readonly OrderCostInterface $decoratedCost
    ) {}

    public function calculateTotal(): float
    {
        return $this->decoratedCost->calculateTotal();
    }

    public function getBreakdown(): array
    {
        return $this->decoratedCost->getBreakdown();
    }
}
