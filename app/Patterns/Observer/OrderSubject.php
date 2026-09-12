<?php

declare(strict_types=1);

namespace App\Patterns\Observer;

use App\Models\Order;
use SplObjectStorage;

/**
 * Sujeto Concreto del patrón Observer (GoF).
 * Gestiona los observadores suscritos y difunde los eventos de ciclo de vida del pedido.
 */
class OrderSubject implements OrderSubjectInterface
{
    /**
     * @var SplObjectStorage<OrderObserverInterface, null>
     */
    private SplObjectStorage $observers;

    /**
     * Registro de bitácora en memoria para visualización inmediata en la interfaz.
     * @var array<int, array{observer: string, event: string, timestamp: string, message: string}>
     */
    private array $executionLogs = [];

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    public function attach(OrderObserverInterface $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(OrderObserverInterface $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(Order $order, string $event): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($order, $event);
            
            $this->executionLogs[] = [
                'observer' => $observer->getName(),
                'event' => $event,
                'timestamp' => date('H:i:s'),
                'order_id' => $order->id ?? 0,
                'message' => "Observador '{$observer->getName()}' ejecutó reacción ante el evento '{$event}'.",
            ];
        }
    }

    /**
     * Retorna la traza de ejecuciones de los observadores en este ciclo.
     */
    public function getExecutionLogs(): array
    {
        return $this->executionLogs;
    }

    /**
     * Conteo de observadores registrados.
     */
    public function countObservers(): int
    {
        return count($this->observers);
    }
}
