<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Order;
use App\Patterns\Observer\Observers\ArtisanNotificationObserver;
use App\Patterns\Observer\Observers\AuditLogObserver;
use App\Patterns\Observer\Observers\StockReductionObserver;
use App\Patterns\Observer\OrderObserverInterface;
use App\Patterns\Observer\OrderSubject;
use PHPUnit\Framework\TestCase;

/**
 * Prueba Unitaria: Patrón Observer (Comportamiento)
 * Verifica el desacoplamiento de eventos posventa y la correcta difusión a los observadores suscritos.
 */
class ObserverPatternTest extends TestCase
{
    public function test_subject_attaches_detaches_and_notifies_observers(): void
    {
        $subject = new OrderSubject();
        $this->assertEquals(0, $subject->countObservers());

        $auditObserver = new AuditLogObserver();
        $artisanObserver = new ArtisanNotificationObserver();
        $stockObserver = new StockReductionObserver();

        // 1. Suscripción
        $subject->attach($auditObserver);
        $subject->attach($artisanObserver);
        $subject->attach($stockObserver);
        $this->assertEquals(3, $subject->countObservers());

        // 2. Notificación
        $order = new Order([
            'id' => 888,
            'order_number' => 'ORD-TEST-OBSERVER',
            'customer_name' => 'Comprador Artesanías',
            'customer_email' => 'comprador@valle.co',
            'total_amount' => 580000.0,
            'payment_method' => 'PSE Bancolombia',
            'payment_reference' => 'PSE-888999',
        ]);

        $subject->notify($order, 'order.completed');

        $logs = $subject->getExecutionLogs();
        $this->assertCount(3, $logs, 'Los 3 observadores debieron registrar su ejecución.');

        $observerNames = array_column($logs, 'observer');
        $this->assertContains($auditObserver->getName(), $observerNames);
        $this->assertContains($artisanObserver->getName(), $observerNames);
        $this->assertContains($stockObserver->getName(), $observerNames);

        // 3. Desuscripción
        $subject->detach($artisanObserver);
        $this->assertEquals(2, $subject->countObservers());
    }

    public function test_custom_mock_observer_can_subscribe_seamlessly(): void
    {
        $subject = new OrderSubject();

        $customObserver = new class implements OrderObserverInterface {
            public bool $wasUpdated = false;
            public string $receivedEvent = '';

            public function update(Order $order, string $event): void
            {
                $this->wasUpdated = true;
                $this->receivedEvent = $event;
            }

            public function getName(): string
            {
                return 'MockTestObserver';
            }
        };

        $subject->attach($customObserver);
        $order = new Order(['id' => 12]);
        $subject->notify($order, 'order.paid');

        $this->assertTrue($customObserver->wasUpdated);
        $this->assertEquals('order.paid', $customObserver->receivedEvent);
    }
}
