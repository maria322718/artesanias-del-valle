<?php

declare(strict_types=1);

namespace App\Patterns\FactoryMethod;

use App\Models\Order;

/**
 * Producto Concreto 2: Tirilla POS Térmica para Ferias Artesanales Directas.
 */
class SimpleTicketReceipt implements ReceiptInterface
{
    private string $ticketNumber;
    private string $timestamp;

    public function __construct(
        private readonly Order $order
    ) {
        $this->ticketNumber = 'POS-VALLE-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT);
        $this->timestamp = date('d/m/Y H:i');
    }

    public function getType(): string
    {
        return 'simple_ticket';
    }

    public function getTitle(): string
    {
        return 'Tirilla POS Artesanal (Feria Directa)';
    }

    public function getDocumentNumber(): string
    {
        return $this->ticketNumber;
    }

    public function getLegalMetadata(): array
    {
        return [
            'tipo' => 'Comprobante Simplificado de Entrega en Taller Artesanal',
            'ticket' => $this->ticketNumber,
            'hora' => $this->timestamp,
            'mensaje_artesano' => 'Piezas elaboradas a mano con técnicas ancestrales colombianas.',
        ];
    }

    public function renderHtml(): string
    {
        $totalFmt = number_format((float) $this->order->total_amount, 0, ',', '.');

        return <<<HTML
        <div class="p-6 bg-amber-50 border border-dashed border-amber-300 rounded-lg shadow-sm text-stone-800 text-xs font-mono max-w-sm mx-auto">
            <div class="text-center pb-3 border-b border-dashed border-amber-300">
                <p class="font-bold text-sm tracking-widest text-amber-950">*** ARTESANÍAS DEL VALLE ***</p>
                <p class="text-[11px] text-stone-600">Feria Cultural y Tradición Colombiana</p>
                <p class="text-[10px] text-stone-500 mt-1">Ticket #{$this->ticketNumber} | {$this->timestamp}</p>
            </div>

            <div class="py-3 border-b border-dashed border-amber-300 space-y-1">
                <div class="flex justify-between">
                    <span>Cliente:</span>
                    <span class="font-bold">{$this->order->customer_name}</span>
                </div>
                <div class="flex justify-between">
                    <span>Transacción:</span>
                    <span>{$this->order->payment_reference}</span>
                </div>
                <div class="flex justify-between text-stone-500">
                    <span>Pago con:</span>
                    <span>{$this->order->payment_method}</span>
                </div>
            </div>

            <div class="pt-3 pb-2 flex justify-between font-bold text-base text-amber-950">
                <span>TOTAL RECIBIDO:</span>
                <span>\${$totalFmt} COP</span>
            </div>

            <div class="text-center pt-3 border-t border-dashed border-amber-300 text-[10px] text-stone-600 space-y-1">
                <p class="italic">"Cada pieza conserva el alma de nuestros pueblos originarios."</p>
                <p>¡Gracias por apoyar directamente al artesano!</p>
            </div>
        </div>
        HTML;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
