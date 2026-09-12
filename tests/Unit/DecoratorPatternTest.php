<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Patterns\Decorator\ArtisanInsuranceDecorator;
use App\Patterns\Decorator\BaseOrderCost;
use App\Patterns\Decorator\GiftWrapDecorator;
use App\Patterns\Decorator\OrderCostDecorator;
use App\Patterns\Decorator\OrderCostInterface;
use PHPUnit\Framework\TestCase;

/**
 * Prueba Unitaria: Patrón Decorator (Estructural)
 * Verifica la composición dinámica de costos adicionales sobre el subtotal base de artesanías.
 */
class DecoratorPatternTest extends TestCase
{
    public function test_base_order_cost_calculates_correctly(): void
    {
        $base = new BaseOrderCost(420000.0); // Costo vajilla Ráquira
        $this->assertInstanceOf(OrderCostInterface::class, $base);
        $this->assertEquals(420000.0, $base->calculateTotal());

        $breakdown = $base->getBreakdown();
        $this->assertCount(1, $breakdown);
        $this->assertEquals('Subtotal Artesanías Colombianas', $breakdown[0]['concept']);
    }

    public function test_gift_wrap_decorator_adds_fixed_fee(): void
    {
        $base = new BaseOrderCost(240000.0); // Mochila Wayuu
        $wrapped = new GiftWrapDecorator($base);

        $this->assertInstanceOf(OrderCostDecorator::class, $wrapped);
        $this->assertInstanceOf(OrderCostInterface::class, $wrapped);

        // 240.000 + 15.000 de empaque de mimbre = 255.000
        $this->assertEquals(255000.0, $wrapped->calculateTotal());

        $breakdown = $wrapped->getBreakdown();
        $this->assertCount(2, $breakdown);
        $this->assertEquals('Empaque de Regalo Ecológico en Mimbre', $breakdown[1]['concept']);
    }

    public function test_artisan_insurance_decorator_applies_percentage(): void
    {
        $base = new BaseOrderCost(580000.0); // Plato Barniz de Pasto
        $insured = new ArtisanInsuranceDecorator($base);

        // 5% de 580.000 = 29.000 COP
        $expectedTotal = 580000.0 + 29000.0;
        $this->assertEquals($expectedTotal, $insured->calculateTotal());

        $breakdown = $insured->getBreakdown();
        $this->assertCount(2, $breakdown);
        $this->assertEquals(29000.0, $breakdown[1]['cost']);
    }

    public function test_multiple_decorators_can_be_stacked_in_any_order(): void
    {
        $subtotal = 385000.0; // Sombrero Vueltiao

        // Stacking Orden 1: Base -> Wrap -> Insurance
        $cost1 = new ArtisanInsuranceDecorator(
            new GiftWrapDecorator(
                new BaseOrderCost($subtotal)
            )
        );

        // Stacking Orden 2: Base -> Insurance -> Wrap
        $cost2 = new GiftWrapDecorator(
            new ArtisanInsuranceDecorator(
                new BaseOrderCost($subtotal)
            )
        );

        $this->assertEquals(3, count($cost1->getBreakdown()));
        $this->assertEquals(3, count($cost2->getBreakdown()));

        // Ambos apilamientos demuestran la composición sobre herencia sin requerir 4 subclases
        $this->assertGreaterThan($subtotal, $cost1->calculateTotal());
        $this->assertGreaterThan($subtotal, $cost2->calculateTotal());
    }
}
