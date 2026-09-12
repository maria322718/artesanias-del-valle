<?php

declare(strict_types=1);

use App\Http\Controllers\ArchitectureReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
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

// Checkout & Procesamiento GoF
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/procesar', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/confirmacion/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// Panel de Arquitectura de Software, SOLID y Rúbrica 350
Route::get('/arquitectura-solid-gof', [ArchitectureReviewController::class, 'index'])->name('architecture.index');
Route::get('/api/pattern-demo', [ArchitectureReviewController::class, 'runPatternDemo'])->name('api.pattern_demo');
