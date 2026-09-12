@extends('layouts.app')

@section('title', '¡Pedido Confirmado! — Artesanías del Valle')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">

    <!-- Header de Éxito -->
    <div class="text-center space-y-3">
        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 border border-emerald-300 flex items-center justify-center text-emerald-700 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-bold uppercase tracking-wider border border-emerald-200">
            Pago Aprobado con Éxito
        </span>
        <h1 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold text-[#231F1D]">
            ¡Gracias por Apoyar el Arte y la Cultura Colombiana!
        </h1>
        <p class="text-[#6E6864] text-xs sm:text-sm max-w-lg mx-auto leading-relaxed">
            Hemos recibido tu pedido correctamente. Hemos notificado al taller artesanal correspondiente para dar inicio al alistamiento e inspección de tus piezas.
        </p>
    </div>

    <!-- Banner Destacado de NIT del Pedido y Rastreo Directo -->
    <div class="p-6 rounded-[18px] bg-gradient-to-r from-[#FAF3EB] via-[#FDF8F3] to-[#FAF0E6] border border-[#ECCFBC] flex flex-col sm:flex-row items-center justify-between gap-5 shadow-sm">
        <div class="space-y-1 text-left">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#8D341B]/10 text-[#8D341B] text-[10.5px] font-bold uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-[#8D341B]"></span>
                NIT Oficial del Pedido (Único e Irrepetible)
            </span>
            <p class="font-mono font-bold text-2xl sm:text-3xl text-[#6C230E] tracking-tight">
                {{ $order->nit ?? ($lastResult['order_nit'] ?? '901.533.464-1') }}
            </p>
            <p class="text-xs text-[#7A6A5E] leading-relaxed">
                Guarda este NIT para comprobar en cualquier momento la validez y estado de tu compra en nuestro apartado de <strong>Consultar Pedido</strong>.
            </p>
        </div>
        <a 
            href="{{ route('orders.tracking', ['nit' => $order->nit ?? ($lastResult['order_nit'] ?? '')]) }}" 
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#8D341B] to-[#A34328] hover:from-[#6C230E] hover:to-[#8D341B] text-white text-xs font-bold shadow-md shadow-[#8D341B]/25 hover:shadow-lg transition-all shrink-0 flex items-center gap-2 active:scale-95"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Consultar Estado con este NIT</span>
        </a>
    </div>

    <!-- Panel de Datos del Pedido -->
    <div class="bg-white p-6 sm:p-7 rounded-[16px] border border-[#E5DDD2] shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-[#E5DDD2] pb-4">
            <div>
                <span class="text-[10px] text-[#6E6864] font-bold uppercase tracking-wider">Número de Pedido</span>
                <p class="text-lg sm:text-xl font-mono font-bold text-[#231F1D]">{{ $order->order_number ?? ($lastResult['order_number'] ?? 'ORD-VALLE-001') }}</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-[#6E6864] font-bold uppercase tracking-wider">Total Pagado</span>
                <p class="text-xl sm:text-2xl font-mono font-bold text-emerald-800">
                    ${{ number_format((float) ($order->total_amount ?? ($lastResult['total_amount'] ?? 0)), 0, ',', '.') }} COP
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-3.5 rounded-lg bg-[#FAF4ED] border border-[#E5DDD2]">
                <span class="text-[#6E6864] font-bold block mb-1">Destinatario:</span>
                <p class="font-bold text-stone-800">{{ $order->customer_name ?? ($lastResult['customer_name'] ?? 'Cliente') }}</p>
                <p class="text-stone-500">{{ $order->customer_email ?? ($lastResult['customer_email'] ?? '') }}</p>
            </div>
            <div class="p-3.5 rounded-lg bg-[#FAF4ED] border border-[#E5DDD2]">
                <span class="text-[#6E6864] font-bold block mb-1">NIT Registrado:</span>
                <p class="font-mono font-bold text-[#8D341B]">{{ $order->nit ?? ($lastResult['order_nit'] ?? 'NIT-PENDIENTE') }}</p>
                <p class="font-mono text-stone-500 text-[11px] truncate">Ref: {{ $order->payment_reference ?? ($lastResult['payment_reference'] ?? 'TX-123') }}</p>
            </div>
            <div class="p-3.5 rounded-lg bg-[#FBF9F5] border border-[#E8E2D9]">
                <span class="text-[#6E6864] font-bold block mb-1">Tipo de Comprobante:</span>
                <p class="font-bold text-stone-800">{{ ($order->invoice_type ?? '') === 'electronic' ? 'Factura Electrónica' : 'Tirilla de Venta Directa' }}</p>
                <p class="text-emerald-700 font-semibold text-[11px]">Emitido y verificado</p>
            </div>
        </div>

        <!-- Desglose de Costos (Sin Emojis) -->
        @php
            $breakdown = $lastResult['cost_breakdown'] ?? ($order->applied_decorators ?? []);
        @endphp
        @if(!empty($breakdown))
            <div class="border-t border-[#E8E2D9] pt-4">
                <h4 class="text-xs font-bold text-stone-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Desglose de Conceptos y Servicios</span>
                </h4>
                <div class="space-y-2">
                    @foreach($breakdown as $layer)
                        <div class="p-3 rounded-lg bg-[#FBF9F5] border border-[#E8E2D9] flex justify-between items-center text-xs">
                            <div>
                                <span class="font-bold text-stone-900">{{ $layer['concept'] }}</span>
                                <p class="text-stone-500 text-[11px] mt-0.5">{{ $layer['description'] }}</p>
                            </div>
                            <span class="font-mono font-bold text-stone-900 shrink-0 ml-4">${{ number_format((float) $layer['cost'], 0, ',', '.') }} COP</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Comprobante Generado (Sin Emojis) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-xl sm:text-2xl font-bold text-[#231F1D] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Comprobante Oficial de Compra</span>
            </h2>
            <span class="text-xs text-emerald-800 font-semibold bg-emerald-50 px-2.5 py-1 rounded border border-emerald-200">Documento Oficial</span>
        </div>
        
        <div class="bg-[#FBF9F5] p-5 sm:p-6 rounded-[14px] border border-[#E8E2D9] shadow-sm">
            @if(!empty($lastResult['receipt']['html']))
                {!! $lastResult['receipt']['html'] !!}
            @else
                <div class="p-5 bg-white border border-[#E8E2D9] rounded-lg font-mono text-xs">
                    <p class="font-bold">Comprobante Contable Asociado al Pedido #{{ $order->id }}</p>
                    <p class="text-stone-500 mt-1">Copia digital guardada en el sistema para control de despacho.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Estado y Seguimiento de la Entrega (Sin Emojis) -->
    <div class="bg-white p-6 sm:p-7 rounded-[14px] border border-[#E8E2D9] shadow-sm space-y-5">
        <h2 class="font-serif text-xl font-bold text-[#231F1D] flex items-center gap-2">
            <svg class="w-5 h-5 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            <span>Estado y Seguimiento de tu Pedido</span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200 space-y-1">
                <div class="w-7 h-7 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-bold text-xs">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="font-bold text-xs text-emerald-950 pt-1">1. Pago Acreditado</h4>
                <p class="text-[11px] text-emerald-800 leading-relaxed">Transacción confirmada y registrada exitosamente en nuestro sistema.</p>
            </div>

            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 space-y-1">
                <div class="w-7 h-7 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-xs">
                    2
                </div>
                <h4 class="font-bold text-xs text-amber-950 pt-1">2. Alistamiento en Taller</h4>
                <p class="text-[11px] text-amber-800 leading-relaxed">Notificación despachada al maestro artesano para inspección de calidad y empaque.</p>
            </div>

            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200 space-y-1">
                <div class="w-7 h-7 rounded-lg bg-stone-400 text-white flex items-center justify-center font-bold text-xs">
                    3
                </div>
                <h4 class="font-bold text-xs text-stone-700 pt-1">3. Despacho Nacional</h4>
                <p class="text-[11px] text-stone-500 leading-relaxed">Embalaje reforzado y entrega a transportadora con número de guía vía WhatsApp y correo.</p>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="pt-4 border-t border-[#E8E2D9] flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-[#8D341B] hover:bg-[#6C230E] text-white text-xs font-semibold transition text-center shadow-sm">
            &larr; Volver a la Tienda y Catálogo
        </a>
        <button onclick="window.print()" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-white hover:bg-stone-50 text-stone-700 text-xs font-semibold transition text-center flex items-center justify-center gap-1.5 border border-[#E8E2D9] shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Imprimir Comprobante</span>
        </button>
    </div>

</div>
@endsection
