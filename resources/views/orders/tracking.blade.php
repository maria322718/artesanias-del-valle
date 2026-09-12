@extends('layouts.app')

@section('title', 'Consultar Estado de Pedido por NIT — Artesanías del Valle')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">

    <!-- Encabezado del Apartado de Consulta -->
    <div class="text-center space-y-3 pt-2">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FAF0EB] text-[#8D341B] border border-[#F2D1C2] text-xs font-bold uppercase tracking-wider shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-[#D9822B]"></span>
            Trazabilidad Directa &bull; Sistema Oficial de Seguimiento
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#231F1D]">
            Consulta de Pedido por NIT
        </h1>
        <p class="text-xs sm:text-sm text-[#6E6864] max-w-xl mx-auto leading-relaxed">
            Ingresa el <strong>NIT único</strong> asignado a tu compra para verificar al instante su confirmación en nuestra base de datos, el taller artesanal de origen y el estado de preparación.
        </p>
    </div>

    <!-- Caja de Búsqueda de Pedido -->
    <div class="bg-gradient-to-b from-white to-[#FDFBF8] p-6 sm:p-8 rounded-[20px] border border-[#E5DDD2] shadow-md shadow-[#8D341B]/5 space-y-5">
        <form action="{{ route('orders.track') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-stone-400">
                    <!-- Icono SVG Documento / NIT -->
                    <svg class="w-5 h-5 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="nit" 
                    value="{{ $query }}" 
                    placeholder="Ingresa el NIT del pedido (ej: 901.533.464-1 o número de orden)..." 
                    required
                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-[#DECFC1] text-xs sm:text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:border-[#8D341B] focus:ring-2 focus:ring-[#8D341B]/20 bg-white font-mono shadow-xs transition"
                >
            </div>
            <button 
                type="submit" 
                class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-[#8D341B] to-[#A34328] hover:from-[#6C230E] hover:to-[#8D341B] text-white text-xs sm:text-sm font-bold shadow-md shadow-[#8D341B]/25 hover:shadow-lg hover:shadow-[#8D341B]/35 transition-all shrink-0 flex items-center justify-center gap-2 active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Consultar Pedido</span>
            </button>
        </form>

        <!-- Accesos directos a NITs recientes registrados en la base de datos -->
        @if($recentOrders->isNotEmpty())
            <div class="pt-3 border-t border-[#EAE0D4] flex flex-wrap items-center gap-2 text-xs">
                <span class="text-[#7A6A5E] font-semibold text-[11px]">NITs registrados en el sistema para probar:</span>
                @foreach($recentOrders as $ro)
                    @if($ro->nit)
                        <a 
                            href="{{ route('orders.tracking', ['nit' => $ro->nit]) }}" 
                            class="px-3 py-1 rounded-full bg-[#FAF2EA] border border-[#DECFC1] text-[#8D341B] hover:bg-[#8D341B] hover:text-white transition-all text-[11px] font-mono font-bold flex items-center gap-1 shadow-2xs"
                        >
                            <span>{{ $ro->nit }}</span>
                            <span class="text-[9px] opacity-70">({{ $ro->customer_name }})</span>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- RESULTADO: PEDIDO ENCONTRADO EN LA BASE DE DATOS -->
    @if($order)
        <div class="bg-gradient-to-b from-white to-[#FDFBF8] rounded-[20px] border border-[#DECFC1] shadow-xl overflow-hidden space-y-7 animate-fade-in">
            
            <!-- Barra Superior de Estado Aprobado -->
            <div class="p-6 bg-gradient-to-r from-[#1B4332] to-[#2D6A4F] text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white shrink-0 border border-white/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-200 block">
                            Estado en Base de Datos: Confirmado & Verificado
                        </span>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold">
                            Pedido Registrado Correctamente
                        </h2>
                    </div>
                </div>

                <div class="text-left sm:text-right text-xs bg-white/10 backdrop-blur px-3.5 py-1.5 rounded-xl border border-white/20">
                    <span class="text-emerald-200 block text-[10px] uppercase font-bold">Fecha de Registro</span>
                    <span class="font-semibold">{{ $order->created_at ? $order->created_at->translatedFormat('d \d\e F \d\e Y — H:i') : 'Reciente' }}</span>
                </div>
            </div>

            <!-- Fila Principal de Identificadores (NIT y Orden) -->
            <div class="px-6 sm:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- NIT del Pedido -->
                <div class="p-4 rounded-xl bg-[#FAF3EB] border border-[#ECCFBC] space-y-1">
                    <span class="text-[10px] font-bold text-[#8D341B] uppercase tracking-wider block">NIT Único del Pedido</span>
                    <p class="font-mono font-bold text-lg text-[#6C230E]">{{ $order->nit ?? 'NIT-PENDIENTE' }}</p>
                    <span class="text-[9px] text-[#7A6A5E] font-medium block">Dígito verificador DIAN (Módulo 11)</span>
                </div>

                <!-- Número de Referencia -->
                <div class="p-4 rounded-xl bg-[#FAF4ED] border border-[#EAD7C8] space-y-1">
                    <span class="text-[10px] font-bold text-stone-700 uppercase tracking-wider block">Código de Orden</span>
                    <p class="font-mono font-bold text-base text-stone-900">{{ $order->order_number }}</p>
                    <span class="text-[9px] text-stone-500 block">Referencia interna de taller</span>
                </div>

                <!-- Método de Pago -->
                <div class="p-4 rounded-xl bg-stone-50 border border-stone-200 space-y-1">
                    <span class="text-[10px] font-bold text-stone-700 uppercase tracking-wider block">Forma de Pago</span>
                    <p class="font-semibold text-xs text-stone-900 truncate">{{ $order->payment_method }}</p>
                    <span class="font-mono text-[10px] text-stone-500 block truncate">Ref: {{ $order->payment_reference ?? 'Aprobada' }}</span>
                </div>

                <!-- Total Pagado -->
                <div class="p-4 rounded-xl bg-gradient-to-br from-[#FFF9F3] to-[#FAF0E4] border border-[#E8CEB8] space-y-1">
                    <span class="text-[10px] font-bold text-[#8D341B] uppercase tracking-wider block">Total Liquidado</span>
                    <p class="font-mono font-bold text-xl text-[#8D341B]">{{ $order->formatted_total }}</p>
                    <span class="text-[10px] font-bold text-emerald-800 block">Pagado &bull; Facturación {{ $order->invoice_type === 'electronic' ? 'Electrónica' : 'Boleta' }}</span>
                </div>
            </div>

            <!-- Datos de Entrega del Cliente -->
            <div class="px-6 sm:px-8">
                <div class="p-4 rounded-xl bg-white border border-[#E5DDD2] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs">
                    <div>
                        <span class="text-[10px] font-bold text-[#7A6A5E] uppercase tracking-wider block">Destinatario & Envío</span>
                        <strong class="text-stone-900 text-sm">{{ $order->customer_name }}</strong>
                        <span class="text-stone-500 ml-2">&bull; {{ $order->customer_email }} &bull; Tel: {{ $order->customer_phone ?? 'Registrado' }}</span>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-[10px] font-bold text-[#7A6A5E] uppercase tracking-wider block">Dirección de Entrega</span>
                        <span class="font-medium text-stone-800">{{ $order->shipping_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Lista de Artesanías Compradas -->
            <div class="px-6 sm:px-8 space-y-3">
                <h3 class="font-serif font-bold text-lg text-[#231F1D] flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Piezas Artesanales Incluidas en este Pedido ({{ $order->items->count() }})</span>
                </h3>

                <div class="divide-y divide-[#EAE0D4] border border-[#E5DDD2] rounded-xl bg-white overflow-hidden">
                    @foreach($order->items as $item)
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                @php
                                    $pImg = $item->product?->image_url ?? '/images/artesanias/sombrero-vueltiao-21.jpg';
                                @endphp
                                <img src="{{ $pImg }}" alt="{{ $item->product_name }}" class="w-14 h-14 rounded-lg object-cover border border-[#DECFC1] shrink-0">
                                <div>
                                    <span class="text-[10px] font-bold text-[#8D341B] uppercase tracking-wider">
                                        {{ $item->product?->origin_region ?? 'Artesanía de Colombia' }}
                                    </span>
                                    <h4 class="font-serif font-bold text-sm text-stone-900">{{ $item->product_name }}</h4>
                                    <p class="text-xs text-stone-500">
                                        Maestro: <strong class="text-stone-700">{{ $item->product?->artisan_name ?? 'Taller Tradicional' }}</strong>
                                        &bull; Cantidad: <strong class="text-[#8D341B]">{{ $item->quantity }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right sm:self-center font-mono shrink-0">
                                <span class="text-[10px] text-stone-400 block uppercase">Subtotal</span>
                                <span class="font-bold text-stone-900">${{ number_format((float) $item->subtotal, 0, ',', '.') }} COP</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Servicios de Valor Agregado Aplicados (Mimbre, Seguro) -->
            @if(!empty($order->applied_decorators))
                <div class="px-6 sm:px-8 space-y-2">
                    <span class="text-[10px] font-bold text-[#7A6A5E] uppercase tracking-wider block">Servicios y Desglose Aplicado</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($order->applied_decorators as $dec)
                            <div class="p-3 rounded-xl bg-[#FAF4ED] border border-[#EAE0D4] text-xs flex justify-between items-center">
                                <div>
                                    <strong class="text-stone-800">{{ $dec['concept'] ?? 'Servicio' }}</strong>
                                    <p class="text-[10px] text-stone-500">{{ $dec['description'] ?? '' }}</p>
                                </div>
                                <span class="font-mono font-bold text-[#8D341B] ml-2">${{ number_format((float) ($dec['cost'] ?? 0), 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pie de la Tarjeta con Botones de Acción -->
            <div class="p-6 bg-[#FAF4ED] border-t border-[#DECFC1] flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-[#6E6864] flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Registro auténtico en base de datos. Cada NIT generado es único e irrepetible.</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 rounded-xl border border-[#DECFC1] text-stone-700 bg-white hover:bg-stone-50 text-xs font-semibold transition">
                        Volver al Catálogo
                    </a>
                    <a href="{{ route('checkout.confirmation', ['order' => $order->id]) }}" class="px-5 py-2.5 rounded-xl bg-[#8D341B] hover:bg-[#6C230E] text-white text-xs font-semibold shadow-sm transition">
                        Ver Comprobante Oficial
                    </a>
                </div>
            </div>

        </div>

    <!-- RESULTADO: PEDIDO NO ENCONTRADO -->
    @elseif($searched)
        <div class="bg-white p-10 rounded-[20px] border border-red-200 text-center space-y-4 shadow-sm">
            <div class="w-14 h-14 mx-auto rounded-full bg-red-50 text-red-600 flex items-center justify-center border border-red-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h2 class="font-serif text-xl font-bold text-stone-900">
                No se encontró ningún pedido con el NIT: <span class="font-mono text-[#8D341B]">"{{ $query }}"</span>
            </h2>
            <p class="text-xs text-[#6E6864] max-w-md mx-auto leading-relaxed">
                Por favor verifica los dígitos e intenta nuevamente. Recuerda que puedes consultar tanto con el <strong>NIT del pedido</strong> (ej: <code>901.533.464-1</code>) como con el código de orden (ej: <code>ORD-VALLE-...</code>).
            </p>
            <div class="pt-2">
                <a href="{{ route('orders.tracking') }}" class="inline-block px-5 py-2.5 rounded-xl bg-[#8D341B] text-white text-xs font-semibold hover:bg-[#6C230E] transition">
                    Limpiar Búsqueda
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
