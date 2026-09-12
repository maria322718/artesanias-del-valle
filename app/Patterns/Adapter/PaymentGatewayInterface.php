<?php

declare(strict_types=1);

namespace App\Patterns\Adapter;

/**
 * Target Interface del patrón Adapter (GoF).
 * Define el contrato estándar y moderno esperado por el dominio de "Artesanías del Valle"
 * para interactuar con pasarelas de cobro bancario.
 */
interface PaymentGatewayInterface
{
    /**
     * Procesa una transacción bancaria con tipos estrictos y firma de dominio estandarizada.
     */
    public function processPayment(float $amount, string $currency, array $metadata = []): GatewayResponse;

    /**
     * Retorna el identificador legible de la pasarela.
     */
    public function getGatewayName(): string;
}
