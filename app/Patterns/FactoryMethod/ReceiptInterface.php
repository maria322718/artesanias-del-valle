<?php

declare(strict_types=1);

namespace App\Patterns\FactoryMethod;

use App\Models\Order;

/**
 * Producto Abstracto del patrón Factory Method (GoF).
 * Define el contrato que deben cumplir todos los comprobantes de pago de la tienda de artesanías.
 */
interface ReceiptInterface
{
    /**
     * Retorna el tipo o formato del comprobante (ej. 'electronic_invoice', 'pos_ticket').
     */
    public function getType(): string;

    /**
     * Retorna el título formal del comprobante.
     */
    public function getTitle(): string;

    /**
     * Retorna el número oficial o consecutivo del comprobante.
     */
    public function getDocumentNumber(): string;

    /**
     * Retorna los metadatos legales o fiscales (CUFE, QR, resolución DIAN, etc.).
     */
    public function getLegalMetadata(): array;

    /**
     * Renderiza la representación visual HTML o textual del comprobante.
     */
    public function renderHtml(): string;

    /**
     * Retorna el modelo de orden asociado.
     */
    public function getOrder(): Order;
}
