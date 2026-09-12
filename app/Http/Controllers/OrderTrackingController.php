<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    /**
     * Muestra la vista de consulta de pedidos por NIT o procesa la búsqueda vía GET.
     */
    public function index(Request $request): View
    {
        $query = trim((string) $request->input('nit', ''));
        $searched = !empty($query);
        $order = null;

        if ($searched) {
            $order = Order::findByNitOrNumber($query);
            if ($order) {
                $order->load(['items.product', 'auditLogs']);
            }
        }

        // Órdenes recientes disponibles para facilitar la prueba directa
        $recentOrders = Order::latest()->take(4)->get(['id', 'order_number', 'nit', 'customer_name', 'total_amount', 'created_at']);

        return view('orders.tracking', compact('order', 'query', 'searched', 'recentOrders'));
    }

    /**
     * Redirige la consulta POST hacia la URL canónica con parámetro GET.
     */
    public function search(Request $request): RedirectResponse
    {
        $nit = trim((string) $request->input('nit', ''));

        if (empty($nit)) {
            return redirect()->route('orders.tracking')->with('info', 'Por favor ingresa el NIT o código de tu pedido.');
        }

        return redirect()->route('orders.tracking', ['nit' => $nit]);
    }
}
