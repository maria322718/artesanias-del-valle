@extends('layouts.app')

@section('title', 'Catálogo de Artesanías de Colombia — Artesanías del Valle')

@section('content')
<div class="space-y-10">

    <!-- Hero Banner Cultural -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-clay-900 via-clay-800 to-clay-700 text-white shadow-xl">
        <div class="absolute inset-0 opacity-20 mix-blend-overlay pointer-events-none bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative max-w-3xl px-8 py-14 sm:px-12 sm:py-20">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-semibold tracking-wider uppercase mb-4 border border-amber-400/30">
                <span>🇨🇴</span> Patrimonio Cultural Inmaterial
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-tight leading-tight">
                Manos Ancestrales: El Alma de Colombia en Cada Pieza
            </h1>
            <p class="mt-4 text-base sm:text-lg text-clay-100 leading-relaxed">
                Descubre obras maestras elaboradas por comunidades Wayuu, Zenú, alfareros de Ráquira y barnizadores de Pasto. Cada compra apoya directamente a los talleres familiares de las regiones.
            </p>
            <div class="mt-6 flex flex-wrap gap-4 items-center">
                <a href="#catalogo" class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-stone-950 font-bold text-sm shadow-md hover:shadow-lg transition">
                    Explorar Catálogo
                </a>
                <a href="{{ route('architecture.index') }}" class="px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-sm backdrop-blur border border-white/20 transition flex items-center gap-2">
                    <span>Ver Arquitectura GoF & SOLID</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div id="catalogo" class="bg-white p-6 rounded-2xl shadow-sm border border-stone-200 flex flex-col md:flex-row gap-4 items-center justify-between">
        <!-- Buscador -->
        <form action="{{ route('catalog.index') }}" method="GET" class="w-full md:w-1/2 flex items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por sombrero, mochila, cerámica, artesano..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-clay-500 focus:border-clay-500 text-sm">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-clay-600 hover:bg-clay-700 text-white rounded-xl text-sm font-semibold transition shrink-0">
                Buscar
            </button>
        </form>

        <!-- Filtro por Región -->
        <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('catalog.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ !request('region') ? 'bg-clay-600 text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                Todas las Regiones
            </a>
            @foreach($regions as $reg)
                <a href="{{ route('catalog.index', ['region' => $reg]) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap {{ request('region') === $reg ? 'bg-clay-600 text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                    {{ $reg }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Rejilla de Artesanías -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col group">
                <!-- Imagen con Badges -->
                <div class="relative h-64 overflow-hidden bg-stone-100">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                        <span class="px-2.5 py-1 bg-stone-900/80 backdrop-blur text-white text-[11px] font-bold rounded-lg tracking-wide flex items-center gap-1">
                            <span>📍</span> {{ $product->origin_region }}
                        </span>
                        @if($product->is_fragile)
                            <span class="px-2.5 py-1 bg-amber-500 text-stone-950 text-[11px] font-bold rounded-lg shadow-sm flex items-center gap-1">
                                <span>⚠️</span> Pieza Frágil (Aplica Seguro)
                            </span>
                        @endif
                    </div>

                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-1 bg-white/90 backdrop-blur text-stone-700 text-[11px] font-semibold rounded-md shadow">
                            Stock: {{ $product->stock }} uds.
                        </span>
                    </div>
                </div>

                <!-- Detalle -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-clay-600 font-bold uppercase tracking-wider mb-1">
                            {{ $product->technique }}
                        </p>
                        <h3 class="font-serif font-bold text-xl text-stone-900 leading-snug group-hover:text-clay-600 transition-colors">
                            {{ $product->name }}
                        </h3>
                        <p class="mt-2 text-xs text-stone-500 flex items-center gap-1">
                            <span class="font-semibold text-stone-700">Artesano:</span> {{ $product->artisan_name }}
                        </p>
                        <p class="mt-3 text-sm text-stone-600 line-clamp-3 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Precio y Acción -->
                    <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-stone-400 uppercase font-bold block">Precio de Taller</span>
                            <span class="text-xl font-bold text-stone-900 font-serif">{{ $product->formatted_price }}</span>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-clay-600 hover:bg-clay-700 text-white text-xs font-bold shadow hover:shadow-md transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Agregar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 bg-white rounded-2xl border border-stone-200 text-center">
                <p class="text-stone-500 text-base">No se encontraron artesanías para los filtros seleccionados.</p>
                <a href="{{ route('catalog.index') }}" class="mt-3 inline-block text-clay-600 font-bold text-sm underline">Restablecer filtros</a>
            </div>
        @endforelse
    </div>

</div>
@endsection
