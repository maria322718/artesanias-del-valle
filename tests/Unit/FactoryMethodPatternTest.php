<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
use App\Patterns\FactoryMethod\ElectronicInvoiceFactory;
use App\Patterns\FactoryMethod\ElectronicInvoiceReceipt;
use App\Patterns\FactoryMethod\ReceiptFactory;
use App\Patterns\FactoryMethod\ReceiptInterface;
use App\Patterns\FactoryMethod\SimpleTicketFactory;
use App\Patterns\FactoryMethod\SimpleTicketReceipt;
use PHPUnit\Framework\TestCase;

/**
 * Prueba Unitaria: Patrón Factory Method (Creacional)
 * Verifica que los creadores concretos instancien productos que satisfacen ReceiptInterface.
 */
class FactoryMethodPatternTest extends TestCase
{
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->order = new Order([
            'id' => 77,
            'order_number' => 'ORD-TEST-77',
            'customer_name' => 'Profesor Evaluador GoF',
            'customer_email' => 'evaluador@universidad.edu.co',
            'subtotal' => 385000.0,
            'total_amount' => 385000.0,
            'payment_method' => 'Tarjeta Visa',
            'payment_reference' => 'CC-TX-99128',
        ]);
    }

    public function test_electronic_invoice_factory_creates_correct_product_and_metadata(): void
    {
        $factory = new ElectronicInvoiceFactory();
        $this->assertInstanceOf(ReceiptFactory::class, $factory);

        $receipt = $factory->createReceipt($this->order);
        $this->assertInstanceOf(ReceiptInterface::class, $receipt);
        $this->assertInstanceOf(ElectronicInvoiceReceipt::class, $receipt);

        $this->assertEquals('electronic_invoice', $receipt->getType());
        $this->assertStringContainsString('FE-ART-', $receipt->getDocumentNumber());

        $metadata = $receipt->getLegalMetadata();
        $this->assertArrayHasKey('cufe', $metadata);
        $this->assertNotEmpty($metadata['cufe']);
        $this->assertArrayHasKey('resolucion_dian', $metadata);

        $doc = $factory->generateDocument($this->order);
        $this->assertArrayHasKey('html', $doc);
        $this->assertStringContainsString('FACTURA ELECTRÓNICA', $doc['html']);
    }

    public function test_simple_ticket_factory_creates_correct_product(): void
    {
        $factory = new SimpleTicketFactory();
        $this->assertInstanceOf(ReceiptFactory::class, $factory);

        $receipt = $factory->createReceipt($this->order);
        $this->assertInstanceOf(ReceiptInterface::class, $receipt);
        $this->assertInstanceOf(SimpleTicketReceipt::class, $receipt);

        $this->assertEquals('simple_ticket', $receipt->getType());
        $this->assertStringContainsString('POS-VALLE-', $receipt->getDocumentNumber());

        $doc = $factory->generateDocument($this->order);
        $this->assertStringContainsString('ARTESANÍAS DEL VALLE', $doc['html']);
    }

    public function test_creator_hierarchy_enforces_polymorphism_without_switches(): void
    {
        /** @var ReceiptFactory[] $factories */
        $factories = [
            new ElectronicInvoiceFactory(),
            new SimpleTicketFactory(),
        ];

        foreach ($factories as $factory) {
            $doc = $factory->generateDocument($this->order);
            $this->assertIsArray($doc);
            $this->assertArrayHasKey('document_number', $doc);
            $this->assertArrayHasKey('metadata', $doc);
        }
    }
}
