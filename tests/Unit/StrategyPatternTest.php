<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Patterns\Strategy\CreditCardPaymentStrategy;
use App\Patterns\Strategy\PaymentResult;
use App\Patterns\Strategy\PaymentStrategyInterface;
use App\Patterns\Strategy\PaymentStrategyRegistry;
use App\Patterns\Strategy\PaypalPaymentStrategy;
use App\Patterns\Strategy\PSEPaymentStrategy;
use PHPUnit\Framework\TestCase;

/**
 * Prueba Unitaria: Patrón Strategy (Comportamiento)
 * Verifica la intercambiabilidad de algoritmos y el Principio de Sustitución de Liskov (LSP).
 */
class StrategyPatternTest extends TestCase
{
    private PaymentStrategyRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new PaymentStrategyRegistry();
        $this->registry->register(new CreditCardPaymentStrategy());
        $this->registry->register(new PSEPaymentStrategy());
        $this->registry->register(new PaypalPaymentStrategy());
    }

    public function test_all_strategies_implement_canonical_interface_and_respect_lsp(): void
    {
        $amount = 385000.0;

        $cases = [
            'credit_card' => [
                'details' => ['card_number' => '4532111122223333', 'card_cvv' => '998', 'card_holder' => 'Ana Sofía Valle'],
            ],
            'pse' => [
                'details' => ['pse_bank' => 'Bancolombia', 'doc_number' => '1098765432'],
            ],
            'paypal' => [
                'details' => ['paypal_email' => 'artesano_comprador@valle.co'],
            ],
        ];

        foreach ($cases as $code => $data) {
            $strategy = $this->registry->get($code);

            // Verificación de Contrato GoF
            $this->assertInstanceOf(PaymentStrategyInterface::class, $strategy);

            // Verificación de LSP: todas las estrategias aceptan los mismos tipos y devuelven PaymentResult
            $result = $strategy->pay($amount, $data['details']);
            $this->assertInstanceOf(PaymentResult::class, $result);
            $this->assertTrue($result->isSuccessful(), "Estrategia {$code} falló al procesar pago válido.");
            $this->assertNotEmpty($result->getTransactionId());
            $this->assertNotEmpty($result->getMessage());
        }
    }

    public function test_registry_resolves_without_conditionals_and_fails_for_unknown(): void
    {
        $this->assertTrue($this->registry->has('credit_card'));
        $this->assertTrue($this->registry->has('pse'));
        $this->assertTrue($this->registry->has('paypal'));
        $this->assertFalse($this->registry->has('cripto_desconocido'));

        $this->expectException(\InvalidArgumentException::class);
        $this->registry->get('cripto_desconocido');
    }

    public function test_credit_card_strategy_validates_cvv_correctly(): void
    {
        $strategy = $this->registry->get('credit_card');
        $result = $strategy->pay(150000.0, [
            'card_number' => '4532111122223333',
            'card_cvv' => '1', // CVV muy corto
        ]);

        $this->assertFalse($result->isSuccessful());
        $this->assertStringContainsString('CVV', $result->getMessage());
    }
}
