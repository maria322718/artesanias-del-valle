@extends('layouts.app')

@section('title', '¡Pedido Confirmado! — Artesanías del Valle')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">

    <!-- Header de Éxito -->
    <div class="text-center space-y-3">
        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 border-2 border-emerald-300 flex items-center justify-center text-emerald-700 shadow-md">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
            ¡Pago Aprobado con Éxito!
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">
            ¡Gracias por Apoyar el Arte y la Cultura Colombiana!
        </h1>
        <p class="text-stone-600 text-sm max-w-lg mx-auto">
            Hemos recibido tu pedido correctamente. Hemos notificado al taller artesanal correspondiente para dar inicio al alistamiento y empaque de tus piezas.
        </p>
    </div>

    <!-- Panel de Datos del Pedido -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-stone-200 pb-4">
            <div>
                <span class="text-xs text-stone-400 font-bold uppercase">Número de Pedido</span>
                <p class="text-xl font-mono font-bold text-stone-900">{{ $order->order_number ?? ($lastResult['order_number'] ?? 'ORD-VALLE-001') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-stone-400 font-bold uppercase">Total Pagado</span>
                <p class="text-2xl font-mono font-bold text-emerald-800">
                    ${{ number_format((float) ($order->total_amount ?? ($lastResult['total_amount'] ?? 0)), 0, ',', '.') }} COP
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-100">
                <span class="text-stone-400 font-bold block mb-1">Destinatario:</span>
                <p class="font-bold text-stone-800">{{ $order->customer_name ?? ($lastResult['customer_name'] ?? 'Cliente') }}</p>
                <p class="text-stone-500">{{ $order->customer_email ?? ($lastResult['customer_email'] ?? '') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-100">
                <span class="text-stone-400 font-bold block mb-1">Método de Pago:</span>
                <p class="font-bold text-stone-800">{{ $order->payment_method ?? ($lastResult['payment_method'] ?? 'Pago Seguro') }}</p>
                <p class="font-mono text-stone-500 text-[11px] truncate">Ref: {{ $order->payment_reference ?? ($lastResult['payment_reference'] ?? 'TX-123') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-100">
                <span class="text-stone-400 font-bold block mb-1">Tipo de Comprobante:</span>
                <p class="font-bold text-stone-800">{{ ($order->invoice_type ?? '') === 'electronic' ? 'Factura Electrónica' : 'Tirilla de Venta Directa' }}</p>
                <p class="text-emerald-700 font-semibold text-[11px]">Emitido y verificado</p>
            </div>
        </div>

        <!-- Desglose de Costos -->
        @php
            $breakdown = $lastResult['cost_breakdown'] ?? ($order->applied_decorators ?? []);
        @endphp
        @if(!empty($breakdown))
            <div class="border-t border-stone-100 pt-4">
                <h4 class="text-xs font-bold text-stone-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <span>✨</span> Desglose de Conceptos y Servicios
                </h4>
                <div class="space-y-2">
                    @foreach($breakdown as $layer)
                        <div class="p-3 rounded-xl bg-stone-50 border border-stone-200/60 flex justify-between items-center text-xs">
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

    <!-- Comprobante Generado -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-2xl font-bold text-stone-900 flex items-center gap-2">
                <span>📄</span> Comprobante Oficial de Compra
            </h2>
            <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">Comprobante Oficial</span>
        </div>
        
        <div class="bg-stone-50 p-6 rounded-3xl border border-stone-200 shadow-sm">
            @if(!empty($lastResult['receipt']['html']))
                {!! $lastResult['receipt']['html'] !!}
            @else
                <div class="p-6 bg-white border border-stone-200 rounded-xl font-mono text-xs">
                    <p class="font-bold">Comprobante Contable Asociado al Pedido #{{ $order->id }}</p>
                    <p class="text-stone-500 mt-1">Copia digital guardada en el sistema para control de despacho.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Estado y Seguimiento de la Entrega -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-6">
        <h2 class="font-serif text-2xl font-bold text-stone-900 flex items-center gap-2">
            <span>📦</span> Estado y Seguimiento de tu Pedido
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-1">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm">
                    ✓
                </div>
                <h4 class="font-bold text-xs text-emerald-950 pt-1">1. Pago Acreditado</h4>
                <p class="text-[11px] text-emerald-800 leading-relaxed">Transacción confirmada y registrada exitosamente en nuestro sistema.</p>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-1">
                <div class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-sm">
                    2
                </div>
                <h4 class="font-bold text-xs text-amber-950 pt-1">2. Alistamiento en Taller</h4>
                <p class="text-[11px] text-amber-800 leading-relaxed">Notificación despachada al maestro artesano para inspección final de calidad y empaque.</p>
            </div>

            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-1">
                <div class="w-8 h-8 rounded-xl bg-stone-400 text-white flex items-center justify-center font-bold text-sm">
                    3
                </div>
                <h4 class="font-bold text-xs text-stone-700 pt-1">3. Despacho Nacional</h4>
                <p class="text-[11px] text-stone-500 leading-relaxed">Embalaje reforzado y entrega a transportadora con número de guía vía WhatsApp y correo.</p>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="pt-6 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-clay-600 hover:bg-clay-700 text-white text-xs font-bold transition text-center shadow">
            &larr; Volver a la Tienda y Catálogo
        </a>
        <button onclick="window.print()" class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition text-center flex items-center justify-center gap-1.5 border border-stone-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Imprimir Comprobante</span>
        </button>
    </div>

</div>
@endsection
