<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Patterns\Adapter\ExternalLegacyBankGateway;
use App\Patterns\Adapter\GatewayResponse;
use App\Patterns\Adapter\LegacyBankAdapter;
use App\Patterns\Adapter\PaymentGatewayInterface;
use PHPUnit\Framework\TestCase;

/**
 * Prueba Unitaria: Patrón Adapter (Estructural)
 * Verifica que el adaptador convierta correctamente la interfaz incompatible del SDK bancario.
 */
class AdapterPatternTest extends TestCase
{
    private ExternalLegacyBankGateway $legacySdk;
    private LegacyBankAdapter $adapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->legacySdk = new ExternalLegacyBankGateway('BANCO-TEST-COLOMBIA-123');
        $this->adapter = new LegacyBankAdapter($this->legacySdk);
    }

    public function test_adapter_implements_target_interface_and_wraps_adaptee(): void
    {
        $this->assertInstanceOf(PaymentGatewayInterface::class, $this->adapter);
        $this->assertSame($this->legacySdk, $this->adapter->getAdaptee());
        $this->assertStringContainsString('LegacyBankAdapter', $this->adapter->getGatewayName());
    }

    public function test_adapter_translates_successful_call_into_gateway_response(): void
    {
        $amount = 195000.0; // Chiva de barro Pitalito
        $currency = 'COP';
        $metadata = ['order_reference' => 'ORD-CHIVA-8812'];

        $response = $this->adapter->processPayment($amount, $currency, $metadata);

        $this->assertInstanceOf(GatewayResponse::class, $response);
        $this->assertTrue($response->isSuccess(), 'El pago adaptado debió ser exitoso.');
        $this->assertNotEmpty($response->getTransactionId());
        $this->assertStringStartsWith('AUTH-', $response->getTransactionId());
        $this->assertStringContainsString('aprobada con éxito', $response->getMessage());

        // Verifica que internamente el adaptee devolvió los campos arcaicos requeridos
        $raw = $response->getRawPayload();
        $this->assertEquals('COD_AUT_200', $raw['COD_RESPUESTA']);
        $this->assertEquals($amount, $raw['REF_ORIGEN'] === 'ORD-CHIVA-8812' ? $amount : 0);
    }

    public function test_adapter_handles_unsupported_currency_gracefully(): void
    {
        $response = $this->adapter->processPayment(100.0, 'USD');

        $this->assertFalse($response->isSuccess());
        $this->assertStringContainsString('divisa', strtolower($response->getMessage()));
    }

    public function test_adapter_handles_invalid_amount(): void
    {
        $response = $this->adapter->processPayment(0.0, 'COP');

        $this->assertFalse($response->isSuccess());
        $this->assertStringContainsString('monto', strtolower($response->getMessage()));
    }
}
