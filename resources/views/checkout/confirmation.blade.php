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
            Transacción Procesada con Éxito
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900">
            ¡Gracias por Preservar la Cultura Colombiana!
        </h1>
        <p class="text-stone-600 text-sm max-w-lg mx-auto">
            Tu pedido ha sido creado y procesado bajo una arquitectura desacoplada y orientada a patrones de diseño.
        </p>
    </div>

    <!-- Panel de Datos del Pedido -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-stone-200 pb-4">
            <div>
                <span class="text-xs text-stone-400 font-bold uppercase">Código de Orden</span>
                <p class="text-xl font-mono font-bold text-stone-900">{{ $order->order_number ?? ($lastResult['order_number'] ?? 'ORD-VALLE-001') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-stone-400 font-bold uppercase">Monto Total Cobrado</span>
                <p class="text-2xl font-mono font-bold text-emerald-800">
                    ${{ number_format((float) ($order->total_amount ?? ($lastResult['total_amount'] ?? 0)), 0, ',', '.') }} COP
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-100">
                <span class="text-stone-400 font-bold block mb-1">Comprador:</span>
                <p class="font-bold text-stone-800">{{ $order->customer_name ?? ($lastResult['customer_name'] ?? 'Cliente') }}</p>
                <p class="text-stone-500">{{ $order->customer_email ?? ($lastResult['customer_email'] ?? '') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-100">
                <span class="text-stone-400 font-bold block mb-1">Estrategia de Pago (Strategy):</span>
                <p class="font-bold text-stone-800">{{ $order->payment_method ?? ($lastResult['payment_method'] ?? 'Estrategia') }}</p>
                <p class="font-mono text-stone-500 text-[11px] truncate">Ref: {{ $order->payment_reference ?? ($lastResult['payment_reference'] ?? 'TX-123') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-100">
                <span class="text-stone-400 font-bold block mb-1">Tipo Comprobante (Factory):</span>
                <p class="font-bold text-stone-800">{{ ($order->invoice_type ?? '') === 'electronic' ? 'Factura Electrónica DIAN' : 'Tirilla POS Artesanal' }}</p>
                <p class="text-stone-500 text-[11px]">Generada polimórficamente</p>
            </div>
        </div>

        <!-- Desglose del Patrón Decorator -->
        @php
            $breakdown = $lastResult['cost_breakdown'] ?? ($order->applied_decorators ?? []);
        @endphp
        @if(!empty($breakdown))
            <div class="border-t border-stone-100 pt-4">
                <h4 class="text-xs font-bold text-stone-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                    <span>✨</span> Desglose de Capas de Costo Aplicadas (Patrón Decorator)
                </h4>
                <div class="space-y-2">
                    @foreach($breakdown as $layer)
                        <div class="p-3 rounded-xl bg-amber-50/60 border border-amber-200/60 flex justify-between items-center text-xs">
                            <div>
                                <span class="font-bold text-amber-950">{{ $layer['concept'] }}</span>
                                <p class="text-stone-600 text-[11px] mt-0.5">{{ $layer['description'] }}</p>
                            </div>
                            <span class="font-mono font-bold text-stone-900 shrink-0 ml-4">${{ number_format((float) $layer['cost'], 0, ',', '.') }} COP</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Renderizado del Comprobante Generado por el Patrón Factory Method -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-2xl font-bold text-stone-900 flex items-center gap-2">
                <span>📄</span> Comprobante Generado (Patrón Factory Method)
            </h2>
            <span class="text-xs text-stone-500 font-mono">ReceiptFactory::generateDocument()</span>
        </div>
        
        <div class="bg-stone-50 p-6 rounded-3xl border border-stone-200 shadow-sm">
            @if(!empty($lastResult['receipt']['html']))
                {!! $lastResult['receipt']['html'] !!}
            @else
                <div class="p-6 bg-white border border-stone-200 rounded-xl font-mono text-xs">
                    <p class="font-bold">Comprobante Contable Asociado al Pedido #{{ $order->id }}</p>
                    <p class="text-stone-500 mt-1">Generado automáticamente bajo el contrato <code class="text-stone-800">ReceiptInterface</code>.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Consola en Vivo de los Observadores Ejecutados (Patrón Observer) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-2xl font-bold text-stone-900 flex items-center gap-2">
                <span>📡</span> Traza de Ejecución en Vivo: Patrón Observer
            </h2>
            <span class="px-2.5 py-1 rounded bg-purple-100 text-purple-800 font-mono text-xs font-bold">
                OrderSubject::notify('order.completed')
            </span>
        </div>
        <p class="text-xs text-stone-500">
            A continuación se presenta el registro en tiempo real de los 3 observadores suscritos que reaccionaron al evento sin acoplamiento con el controlador:
        </p>

        <div class="space-y-3">
            @php
                $logs = $lastResult['observer_logs'] ?? [];
            @endphp

            @forelse($logs as $log)
                <div class="p-4 rounded-2xl bg-stone-900 text-stone-200 border border-stone-800 text-xs font-mono shadow-md flex items-start gap-4">
                    <span class="w-8 h-8 rounded-xl bg-purple-900/60 border border-purple-500/50 flex items-center justify-center text-purple-300 shrink-0 font-bold">
                        ✓
                    </span>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-amber-400">{{ $log['observer'] }}</span>
                            <span class="text-stone-500 text-[10px]">{{ $log['timestamp'] }}</span>
                        </div>
                        <p class="text-stone-300 font-sans text-xs">{{ $log['message'] }}</p>
                    </div>
                </div>
            @empty
                <!-- Fallback representativo si se consultó directamente por ID -->
                <div class="p-4 rounded-2xl bg-stone-900 text-stone-200 border border-stone-800 text-xs font-mono space-y-2">
                    <p class="text-amber-400 font-bold">✓ AuditLogObserver: Registro inmutable guardado en PostgreSQL (audit_logs)</p>
                    <p class="text-emerald-400 font-bold">✓ ArtisanNotificationObserver: Alertas SMS despachadas a los talleres de artesanos</p>
                    <p class="text-blue-400 font-bold">✓ StockReductionObserver: Descuento atómico de piezas físicas completado</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="pt-6 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-stone-200 hover:bg-stone-300 text-stone-800 text-xs font-bold transition text-center">
            &larr; Volver a la Tienda de Artesanías
        </a>
        <a href="{{ route('architecture.index') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition text-center flex items-center justify-center gap-1.5 shadow">
            <span>Ver Inspector Académico de Arquitectura</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>

</div>
@endsection
