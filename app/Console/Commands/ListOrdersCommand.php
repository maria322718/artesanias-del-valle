<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ListOrdersCommand extends Command
{
    protected $signature = 'orders:list';
    protected $description = 'Muestra en consola las órdenes, productos vendidos y facturas registradas en la base de datos';

    public function handle(): int
    {
        $orders = Order::with('items')->orderBy('id', 'desc')->get();

        if ($orders->isEmpty()) {
            $this->warn('No hay pedidos registrados en la base de datos aún.');
            return 0;
        }

        $this->info("================================================================================");
        $this->info(" ARTESANÍAS DEL VALLE — REGISTRO OFICIAL DE FACTURAS Y PRODUCTOS VENDIDOS");
        $this->info("================================================================================");

        $headers = ['ID', 'N° Pedido', 'NIT Oficial', 'Cliente', 'Total Pagado', 'Productos Vendidos', 'Comprobante'];

        $rows = $orders->map(function (Order $order) {
            $products = $order->items->map(fn($item) => "{$item->product_name} (x{$item->quantity})")->implode(', ');
            return [
                $order->id,
                $order->order_number,
                $order->nit,
                $order->customer_name,
                $order->formatted_total,
                $products,
                $order->invoice_type ?? 'Factura Electrónica',
            ];
        });

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("Total de órdenes registradas: " . $orders->count());
        $this->info("Total facturado acumulado: $" . number_format($orders->sum('total_amount'), 0, ',', '.') . " COP");

        return 0;
    }
}
