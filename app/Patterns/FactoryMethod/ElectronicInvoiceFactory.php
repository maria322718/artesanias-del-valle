<?php

declare(strict_types=1);

namespace App\Patterns\FactoryMethod;

use App\Models\Order;

/**
 * Creador Concreto 1: Factoría para Facturas Electrónicas DIAN.
 * Cumple Factory Method al instanciar el producto concreto ElectronicInvoiceReceipt
 * sin recurrir a estructuras de control condicionales.
 */
class ElectronicInvoiceFactory extends ReceiptFactory
{
    public function createReceipt(Order $order): ReceiptInterface
    {
        return new ElectronicInvoiceReceipt($order);
    }
}
