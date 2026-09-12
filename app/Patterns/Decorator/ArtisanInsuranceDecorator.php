<?php

declare(strict_types=1);

namespace App\Patterns\Decorator;

/**
 * Decorador Concreto 2: Seguro Especializado contra Rotura de Piezas Frágiles.
 * Diseñado especialmente para cerámica de Ráquira, vajillas de barro negro y barniz de Pasto.
 * Agrega el 5% del valor base de las piezas (o mínimo $12,000 COP).
 */
class ArtisanInsuranceDecorator extends OrderCostDecorator
{
    public const INSURANCE_RATE = 0.05; // 5%
    public const MINIMUM_FEE = 12000.0;

    public function calculateTotal(): float
    {
        $insuranceFee = $this->calculateInsuranceFee();
        return round($this->decoratedCost->calculateTotal() + $insuranceFee, 2);
    }

    public function getBreakdown(): array
    {
        $insuranceFee = $this->calculateInsuranceFee();
        $breakdown = $this->decoratedCost->getBreakdown();
        $breakdown[] = [
            'concept' => 'Seguro contra Rotura de Piezas Frágiles',
            'cost' => $insuranceFee,
            'description' => 'Cobertura 100% de reposición inmediata ante fisuras o roturas de cerámicas y artesanías delicadas durante el transporte.',
        ];

        return $breakdown;
    }

    private function calculateInsuranceFee(): float
    {
        // Calcula el seguro con base en el subtotal base
        $base = $this->decoratedCost->calculateTotal();
        $calculated = $base * self::INSURANCE_RATE;
        return round(max(self::MINIMUM_FEE, $calculated), 2);
    }
}
