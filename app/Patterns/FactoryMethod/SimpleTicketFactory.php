<?php

declare(strict_types=1);

namespace App\Patterns\FactoryMethod;

use App\Models\Order;

/**
 * Creador Concreto 2: Factoría para Tirillas POS Térmicas de Venta Directa.
 * Instancia el producto concreto SimpleTicketReceipt de forma limpia y polimórfica.
 */
class SimpleTicketFactory extends ReceiptFactory
{
    public function createReceipt(Order $order): ReceiptInterface
    {
        return new SimpleTicketReceipt($order);
    }
}
