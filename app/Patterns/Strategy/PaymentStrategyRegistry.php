<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

use InvalidArgumentException;

/**
 * Registro dinámico de estrategias de pago.
 * Resuelve las estrategias sin utilizar condicionales switch/case ni cascadas de if/else,
 * cumpliendo estrictamente el Principio Abierto/Cerrado (OCP).
 */
class PaymentStrategyRegistry
{
    /**
     * Mapa asociativo de estrategias registradas: [methodCode => PaymentStrategyInterface]
     * @var array<string, PaymentStrategyInterface>
     */
    private array $strategies = [];

    /**
     * Registra una nueva estrategia en tiempo de ejecución o arranque de la aplicación.
     */
    public function register(PaymentStrategyInterface $strategy): self
    {
        $this->strategies[$strategy->getMethodCode()] = $strategy;
        return $this;
    }

    /**
     * Resuelve la estrategia por su código en tiempo constante O(1).
     *
     * @throws InvalidArgumentException Si el método no está registrado.
     */
    public function get(string $methodCode): PaymentStrategyInterface
    {
        if (!isset($this->strategies[$methodCode])) {
            throw new InvalidArgumentException("No existe ninguna estrategia de pago registrada para el código '{$methodCode}'.");
        }

        return $this->strategies[$methodCode];
    }

    /**
     * Verifica si una estrategia está disponible.
     */
    public function has(string $methodCode): bool
    {
        return isset($this->strategies[$methodCode]);
    }

    /**
     * Retorna todas las estrategias registradas para poblar la interfaz de checkout.
     * @return array<string, PaymentStrategyInterface>
     */
    public function all(): array
    {
        return $this->strategies;
    }
}
