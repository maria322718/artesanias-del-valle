<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $subtotal = 0.0;
        $hasFragileItems = false;

        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
            if (!empty($item['is_fragile'])) {
                $hasFragileItems = true;
            }
        }

        return view('cart.index', compact('cart', 'subtotal', 'hasFragileItems'));
    }

    public function add(Request $request): RedirectResponse
    {
        $productId = (int) $request->input('product_id');
        $quantity = max(1, (int) $request->input('quantity', 1));

        $product = Product::findOrFail($productId);
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image_url' => $product->image_url,
                'artisan_name' => $product->artisan_name,
                'origin_region' => $product->origin_region,
                'is_fragile' => (bool) $product->is_fragile,
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', "¡'{$product->name}' agregada al carrito!");
    }

    public function update(Request $request): RedirectResponse
    {
        $productId = (int) $request->input('product_id');
        $quantity = max(1, (int) $request->input('quantity', 1));

        $cart = $request->session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Carrito actualizado con éxito.');
    }

    public function remove(Request $request, int $productId): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Artesanía retirada del carrito.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return redirect()->route('cart.index')->with('info', 'Carrito vaciado.');
    }
}
