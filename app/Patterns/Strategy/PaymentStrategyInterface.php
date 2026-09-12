<?php

declare(strict_types=1);

namespace App\Patterns\Strategy;

/**
 * Interfaz de la familia de algoritmos de pago del patrón Strategy (GoF).
 * Permite intercambiar estrategias en tiempo de ejecución sin acoplar el cliente.
 */
interface PaymentStrategyInterface
{
    /**
     * Ejecuta la transacción de pago para el monto y parámetros indicados.
     */
    public function pay(float $amount, array $paymentDetails): PaymentResult;

    /**
     * Retorna el código identificador único de la estrategia (ej. 'credit_card', 'pse', 'paypal').
     */
    public function getMethodCode(): string;

    /**
     * Retorna el nombre legible para el usuario en la interfaz.
     */
    public function getMethodName(): string;
}
