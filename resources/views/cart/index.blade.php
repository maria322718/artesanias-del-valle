@extends('layouts.app')

@section('title', 'Tu Carrito de Artesanías — Artesanías del Valle')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <div class="border-b border-stone-200 pb-4 flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl font-bold text-stone-900">Carrito de Artesanías</h1>
            <p class="text-sm text-stone-500 mt-1">Revisa tus piezas tradicionales antes de pasar al proceso de pago.</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-clay-600 hover:text-clay-700 flex items-center gap-1">
            <span>&larr; Seguir explorando</span>
        </a>
    </div>

    @if(empty($cart))
        <div class="p-16 bg-white rounded-3xl border border-stone-200 text-center space-y-4 shadow-sm">
            <div class="w-16 h-16 mx-auto bg-stone-100 rounded-full flex items-center justify-center text-stone-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-stone-800">Aún no has seleccionado artesanías</h2>
            <p class="text-sm text-stone-500 max-w-sm mx-auto">Visita nuestro catálogo y apoya a los maestros artesanos de Colombia adquiriendo piezas de tradición viva.</p>
            <a href="{{ route('catalog.index') }}" class="inline-block px-6 py-3 rounded-xl bg-clay-600 text-white text-sm font-bold shadow hover:bg-clay-700 transition">
                Ir al Catálogo
            </a>
        </div>
    @else
        <!-- Aviso de Piezas Frágiles -->
        @if($hasFragileItems)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-3 shadow-sm">
                <span class="text-lg">🛡️</span>
                <div>
                    <span class="font-bold">Tu carrito contiene piezas delicadas de cerámica o alfarería.</span>
                    <p class="mt-0.5 text-stone-600">En el siguiente paso podrás incluir embalaje reforzado y <strong>Seguro contra Rotura</strong> para garantizar su reposición en caso de cualquier incidente durante el transporte.</p>
                </div>
            </div>
        @endif

        <!-- Lista de Productos en el Carrito -->
        <div class="bg-white rounded-3xl border border-stone-200 shadow-sm divide-y divide-stone-100 overflow-hidden">
            @foreach($cart as $item)
                <div class="p-6 flex flex-col sm:flex-row items-center gap-6">
                    <img src="{{ $item['image_url'] ?? 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531' }}" alt="{{ $item['name'] }}" class="w-24 h-24 rounded-2xl object-cover border border-stone-200 shrink-0">

                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <span class="text-[11px] font-bold text-clay-600 uppercase">{{ $item['origin_region'] ?? 'Colombia' }}</span>
                            @if(!empty($item['is_fragile']))
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Pieza Delicada</span>
                            @endif
                        </div>
                        <h3 class="font-serif font-bold text-lg text-stone-900 mt-1">{{ $item['name'] }}</h3>
                        <p class="text-xs text-stone-500">Maestro Artesano: {{ $item['artisan_name'] ?? 'Maestro de la Región' }}</p>
                        <p class="text-xs font-semibold text-stone-700 mt-1">Precio: ${{ number_format((float) $item['price'], 0, ',', '.') }} COP</p>
                    </div>

                    <!-- Cantidad y Subtotal -->
                    <div class="flex items-center gap-6">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-stone-200 rounded-xl overflow-hidden">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="w-16 text-center text-sm py-1.5 focus:outline-none" onchange="this.form.submit()">
                        </form>

                        <div class="text-right min-w-[120px]">
                            <span class="text-[11px] text-stone-400 block uppercase font-semibold">Subtotal</span>
                            <span class="text-base font-bold text-stone-900 font-mono">${{ number_format((float) ($item['price'] * $item['quantity']), 0, ',', '.') }} COP</span>
                        </div>

                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-stone-400 hover:text-red-600 rounded-lg transition" title="Eliminar artesanía">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Resumen y Checkout -->
        <div class="bg-stone-100 p-8 rounded-3xl border border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <span class="text-xs text-stone-500 uppercase font-bold tracking-wider">Subtotal Productos</span>
                <p class="text-3xl font-serif font-bold text-stone-900">${{ number_format($subtotal, 0, ',', '.') }} <span class="text-sm font-sans font-normal text-stone-500">COP</span></p>
                <p class="text-xs text-stone-500 mt-1">Los servicios adicionales de empaque y medios de pago se seleccionan en el checkout.</p>
            </div>

            <div class="flex items-center gap-4 w-full sm:w-auto">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-3 rounded-xl border border-stone-300 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">
                        Vaciar
                    </button>
                </form>
                <a href="{{ route('checkout.index') }}" class="flex-1 sm:flex-initial px-8 py-3.5 rounded-xl bg-clay-600 hover:bg-clay-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition text-center flex items-center justify-center gap-2">
                    <span>Continuar al Pago</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
