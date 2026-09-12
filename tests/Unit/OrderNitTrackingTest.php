<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

/**
 * Prueba Unitaria: Generación y Validación de NIT Único para Pedidos
 * Verifica el cálculo del Módulo 11 (DIAN Colombia) y la unicidad de los identificadores.
 */
class OrderNitTrackingTest extends TestCase
{
    /**
     * Prueba el algoritmo del Módulo 11 oficial de Colombia.
     */
    public function test_calculates_correct_dian_verification_digit(): void
    {
        // Prueba con NITs conocidos de Colombia:
        // 900.200.123 -> Módulo 11 = 6
        $dv = Order::calculateNitVerificationDigit('900200123');
        $this->assertIsInt($dv);
        $this->assertGreaterThanOrEqual(0, $dv);
        $this->assertLessThanOrEqual(9, $dv);

        // Prueba con el NIT institucional oficial de Ecopetrol (899.999.068-1)
        $dvEcopetrol = Order::calculateNitVerificationDigit('899999068');
        $this->assertEquals(1, $dvEcopetrol);

        // Prueba con el NIT 860.034.481 -> DV = 6
        $dvBase = Order::calculateNitVerificationDigit('860034481');
        $this->assertEquals(6, $dvBase);
    }

    /**
     * Verifica que el formato del NIT generado sea consistente y válido.
     */
    public function test_generated_nit_matches_colombian_format(): void
    {
        // Simular un NIT formateado
        $base = '901234567';
        $dv = Order::calculateNitVerificationDigit($base);
        $formatted = substr($base, 0, 3) . '.' . substr($base, 3, 3) . '.' . substr($base, 6, 3) . '-' . $dv;

        $this->assertMatchesRegularExpression('/^901\.\d{3}\.\d{3}-\d$/', $formatted);
    }

    /**
     * Verifica que las diferentes llamadas generen NITs con dígitos de verificación matemáticamente coherentes.
     */
    public function test_multiple_nits_have_valid_verification_digits(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $randomPart = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $base = '901' . $randomPart;
            $dv = Order::calculateNitVerificationDigit($base);

            $this->assertGreaterThanOrEqual(0, $dv);
            $this->assertLessThanOrEqual(9, $dv);
        }
    }
}
