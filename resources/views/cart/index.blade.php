@extends('layouts.app')

@section('title', 'Tu Carrito de Artesanías — Artesanías del Valle')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Encabezado de Carrito -->
    <div class="border-b border-[#E8E2D9] pb-4 flex items-center justify-between">
        <div>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#231F1D]">Carrito de Artesanías</h1>
            <p class="text-xs text-[#6E6864] mt-1">Revisa tus piezas tradicionales antes de pasar al proceso de pago.</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-xs font-semibold text-[#8D341B] hover:text-[#6C230E] flex items-center gap-1 transition">
            <span>&larr; Seguir explorando</span>
        </a>
    </div>

    @if(empty($cart))
        <!-- Carrito Vacío -->
        <div class="p-14 bg-white rounded-[14px] border border-[#E8E2D9] text-center space-y-4 shadow-sm">
            <div class="w-14 h-14 mx-auto bg-[#FBF6F3] rounded-full flex items-center justify-center text-[#8D341B]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="font-serif text-xl font-bold text-[#231F1D]">Aún no has seleccionado artesanías</h2>
            <p class="text-xs text-[#6E6864] max-w-sm mx-auto leading-relaxed">
                Visita nuestro catálogo y apoya a los maestros artesanos de Colombia adquiriendo piezas de tradición viva.
            </p>
            <div class="pt-2">
                <a href="{{ route('catalog.index') }}" class="inline-block px-6 py-2.5 rounded-lg bg-[#8D341B] hover:bg-[#6C230E] text-white text-xs font-semibold shadow-sm transition">
                    Explorar el Catálogo
                </a>
            </div>
        </div>
    @else
        <!-- Aviso de Piezas Frágiles (Con Icono SVG y Resalte Cálido) -->
        @if($hasFragileItems)
            <div class="p-5 rounded-[16px] bg-gradient-to-r from-[#FFF4EE] to-[#FAF0E8] border border-[#F2C7B6] text-[#8D341B] text-xs flex items-start gap-3.5 shadow-sm">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#8D341B] to-[#6C230E] text-white flex items-center justify-center shrink-0 mt-0.5 shadow-md shadow-[#8D341B]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold block text-stone-900 text-sm">Tu carrito contiene piezas delicadas de cerámica o alfarería.</span>
                    <p class="mt-1 text-[#5C4A3E] leading-relaxed">
                        En el siguiente paso podrás incluir embalaje reforzado y <strong>Seguro contra Rotura</strong> para garantizar reposición total durante el transporte nacional.
                    </p>
                </div>
            </div>
        @endif

        <!-- Lista de Productos en el Carrito -->
        <div class="bg-gradient-to-b from-white to-[#FDFBF8] rounded-[16px] border border-[#E5DDD2] shadow-sm divide-y divide-[#EAE0D4] overflow-hidden">
            @foreach($cart as $item)
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-5 hover:bg-[#FAF4EC]/50 transition-colors">
                    <img 
                        src="{{ $item['image_url'] ?? 'https://images.unsplash.com/photo-1544816155-12df9643f363' }}" 
                        alt="{{ $item['name'] }}" 
                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-[#E5DDD2] shrink-0 shadow-xs"
                    >

                    <div class="flex-1 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <span class="text-[10.5px] font-bold text-[#8D341B] uppercase tracking-wider bg-[#FAF0EB] px-2.5 py-0.5 rounded-full border border-[#F2D1C2]">
                                {{ $item['origin_region'] ?? 'Colombia' }}
                            </span>
                            @if(!empty($item['is_fragile']))
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FFF3ED] text-[#8D341B] border border-[#F4C5B3]">
                                    Pieza Delicada
                                </span>
                            @endif
                        </div>
                        <h3 class="font-serif font-bold text-base sm:text-lg text-[#231F1D] mt-1.5">{{ $item['name'] }}</h3>
                        <p class="text-xs text-[#6E6864]">Maestro: <strong class="text-stone-800">{{ $item['artisan_name'] ?? 'Taller Artesanal' }}</strong></p>
                        <p class="text-xs font-semibold text-stone-800 mt-1">Precio unitario: <span class="text-[#8D341B] font-mono">${{ number_format((float) $item['price'], 0, ',', '.') }} COP</span></p>
                    </div>

                    <!-- Selector de Cantidad, Subtotal y Acción -->
                    <div class="flex items-center gap-5 w-full sm:w-auto justify-between sm:justify-end">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-[#DECFC1] rounded-xl overflow-hidden bg-white shadow-2xs">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="w-14 text-center text-xs py-2 font-bold focus:outline-none focus:bg-[#FAF4EC]" onchange="this.form.submit()">
                        </form>

                        <div class="text-right min-w-[110px]">
                            <span class="text-[10px] text-[#7A6A5E] block uppercase font-bold">Subtotal</span>
                            <span class="text-base font-bold text-[#6C230E] font-mono">
                                ${{ number_format((float) ($item['price'] * $item['quantity']), 0, ',', '.') }} COP
                            </span>
                        </div>

                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-stone-400 hover:text-red-600 rounded-xl hover:bg-red-50 transition" title="Eliminar del carrito">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Panel de Subtotal y Botón de Checkout con Gradiente Cálido -->
        <div class="bg-gradient-to-br from-white via-[#FFFDF9] to-[#FAF3EB] p-6 sm:p-8 rounded-[16px] border border-[#E5DDD2] flex flex-col sm:flex-row items-center justify-between gap-6 shadow-md shadow-[#8D341B]/5">
            <div>
                <span class="text-[11px] text-[#7A6A5E] uppercase font-bold tracking-wider">Subtotal de Artesanías</span>
                <p class="text-2xl sm:text-3xl font-serif font-bold text-[#6C230E]">
                    ${{ number_format($subtotal, 0, ',', '.') }} <span class="text-xs font-sans font-normal text-[#7A6A5E]">COP</span>
                </p>
                <p class="text-xs text-[#6E6864] mt-1">Los servicios adicionales de embalaje y métodos de pago se confirman en el siguiente paso.</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-3 rounded-xl border border-[#DECFC1] text-stone-600 text-xs font-semibold hover:bg-[#F6EFE7] transition">
                        Vaciar
                    </button>
                </form>
                <a href="{{ route('checkout.index') }}" class="flex-1 sm:flex-initial px-8 py-3.5 rounded-xl bg-gradient-to-r from-[#8D341B] to-[#A34328] hover:from-[#6C230E] hover:to-[#8D341B] text-white font-bold text-xs shadow-lg shadow-[#8D341B]/30 hover:shadow-xl hover:shadow-[#8D341B]/40 hover:-translate-y-0.5 transition-all text-center flex items-center justify-center gap-2">
                    <span>Continuar al Pago</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
