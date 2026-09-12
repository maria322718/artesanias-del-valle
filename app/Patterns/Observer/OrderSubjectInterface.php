<?php

declare(strict_types=1);

namespace App\Patterns\Observer;

use App\Models\Order;

/**
 * Interfaz del Sujeto Observable del patrón Observer (GoF).
 */
interface OrderSubjectInterface
{
    public function attach(OrderObserverInterface $observer): void;
    public function detach(OrderObserverInterface $observer): void;
    public function notify(Order $order, string $event): void;
}
