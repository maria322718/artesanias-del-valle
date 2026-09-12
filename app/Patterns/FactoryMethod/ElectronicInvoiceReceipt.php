<?php

declare(strict_types=1);

namespace App\Patterns\FactoryMethod;

use App\Models\Order;

/**
 * Producto Concreto 1: Factura Electrónica de Venta con Validación Previa DIAN (Colombia).
 */
class ElectronicInvoiceReceipt implements ReceiptInterface
{
    private string $invoiceNumber;
    private string $cufe;
    private string $issueDate;

    public function __construct(
        private readonly Order $order
    ) {
        $this->invoiceNumber = 'FE-ART-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
        $this->issueDate = date('Y-m-d H:i:s');
        // Cálculo canónico simulado de Código Único de Factura Electrónica (CUFE)
        $this->cufe = hash('sha384', $this->invoiceNumber . $this->order->total_amount . $this->order->customer_email . $this->issueDate);
    }

    public function getType(): string
    {
        return 'electronic_invoice';
    }

    public function getTitle(): string
    {
        return 'Factura Electrónica de Venta — DIAN';
    }

    public function getDocumentNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getLegalMetadata(): array
    {
        return [
            'resolucion_dian' => '18764000001234 de 2026-01-15',
            'rango_autorizado' => 'FE-ART-000001 al FE-ART-999999',
            'vigencia' => '24 meses',
            'cufe' => $this->cufe,
            'qr_code_data' => "NumFac:{$this->invoiceNumber};FecFac:{$this->issueDate};NitFac:900123456-1;ValFac:{$this->order->total_amount};CUFE:{$this->cufe}",
            'regimen' => 'Responsable de IVA - Sector Artesanal y Cultural',
            'emisor' => 'Asociación de Artesanos del Valle S.A.S. - NIT: 900.123.456-1',
        ];
    }

    public function renderHtml(): string
    {
        $subtotalFmt = number_format((float) $this->order->subtotal, 0, ',', '.');
        $totalFmt = number_format((float) $this->order->total_amount, 0, ',', '.');
        $taxFmt = number_format((float) ($this->order->total_amount * 0.19 / 1.19), 0, ',', '.');

        return <<<HTML
        <div class="p-6 bg-white border border-emerald-300 rounded-xl shadow-sm text-stone-800 text-sm font-mono">
            <div class="border-b border-stone-200 pb-4 mb-4 flex justify-between items-start">
                <div>
                    <h3 class="text-base font-bold text-emerald-900">FACTURA ELECTRÓNICA DE VENTA</h3>
                    <p class="text-xs text-stone-500">Asociación de Artesanos del Valle S.A.S.</p>
                    <p class="text-xs text-stone-500">NIT: 900.123.456-1 | Res. DIAN 18764000001234</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-2 py-1 bg-emerald-100 text-emerald-800 font-bold rounded text-xs">{$this->invoiceNumber}</span>
                    <p class="text-xs text-stone-400 mt-1">{$this->issueDate}</p>
                </div>
            </div>

            <div class="mb-4 text-xs bg-stone-50 p-3 rounded">
                <p><strong>Adquirente:</strong> {$this->order->customer_name} ({$this->order->customer_email})</p>
                <p><strong>Método de Pago:</strong> {$this->order->payment_method} | <strong>Ref:</strong> {$this->order->payment_reference}</p>
            </div>

            <div class="border-t border-b border-stone-200 py-2 my-2 text-xs">
                <div class="flex justify-between py-1"><span>Subtotal Artesanías:</span><span>\${$subtotalFmt} COP</span></div>
                <div class="flex justify-between py-1"><span>IVA 19% Incluido:</span><span>\${$taxFmt} COP</span></div>
                <div class="flex justify-between py-1 font-bold text-stone-900 border-t border-stone-200 mt-1 pt-1 text-sm">
                    <span>TOTAL A PAGAR:</span><span class="text-emerald-800">\${$totalFmt} COP</span>
                </div>
            </div>

            <div class="mt-4 pt-2 text-[10px] text-stone-500 leading-tight">
                <p class="break-all"><strong>CUFE:</strong> {$this->cufe}</p>
                <p class="mt-1 text-center text-stone-400">Documento validado previamente por la Dirección de Impuestos y Aduanas Nacionales (DIAN)</p>
            </div>
        </div>
        HTML;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
