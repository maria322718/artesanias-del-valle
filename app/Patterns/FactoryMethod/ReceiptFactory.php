<?php

declare(strict_types=1);

namespace App\Patterns\FactoryMethod;

use App\Models\Order;

/**
 * Creador Abstracto (Creator) del patrón Factory Method (GoF).
 * Declara el método abstracto de fábrica createReceipt() que las subclases concretas
 * deben implementar para retornar una instancia polimórfica de ReceiptInterface.
 * 
 * Regla de Oro: NINGÚN condicional switch/case ni cascada if/else dentro de la factoría.
 */
abstract class ReceiptFactory
{
    /**
     * El Método de Fábrica (Factory Method) canónico.
     */
    abstract public function createReceipt(Order $order): ReceiptInterface;

    /**
     * Operación Template que utiliza el producto creado por el método de fábrica
     * para orquestar la generación formal del documento contable.
     * 
     * @return array<string, mixed>
     */
    public function generateDocument(Order $order): array
    {
        // 1. Invoca el método de fábrica polimórfico
        $receipt = $this->createReceipt($order);

        // 2. Ejecuta la lógica común de negocio sobre el producto abstracto
        return [
            'type' => $receipt->getType(),
            'title' => $receipt->getTitle(),
            'document_number' => $receipt->getDocumentNumber(),
            'metadata' => $receipt->getLegalMetadata(),
            'html' => $receipt->renderHtml(),
            'generated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
