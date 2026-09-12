<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderTrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Artesanías del Valle
|--------------------------------------------------------------------------
*/

// Catálogo Principal
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/artesania/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// Carrito de Compras
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/actualizar', [CartController::class, 'update'])->name('cart.update');
Route::post('/carrito/eliminar/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');

// Checkout & Procesamiento de Órdenes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/procesar', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/confirmacion/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// Consulta y Trazabilidad de Pedidos por NIT
Route::get('/consultar-pedido', [OrderTrackingController::class, 'index'])->name('orders.tracking');
Route::post('/consultar-pedido', [OrderTrackingController::class, 'search'])->name('orders.track');
