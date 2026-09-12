@extends('layouts.app')

@section('title', 'Catálogo de Maestros Artesanos de Colombia — Artesanías del Valle')

@section('content')
<div class="space-y-12">

    <!-- Hero Cultural Principal con Gradiente Cálido y Atmósfera Artesanal -->
    <section class="relative rounded-[20px] bg-gradient-to-br from-[#531808] via-[#78260F] to-[#A43E1F] text-white overflow-hidden shadow-xl border border-[#B94E2C]/40">
        <!-- Resplandor ambiental de luz cálida -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-orange-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch min-h-[380px] relative z-10">
            
            <!-- Columna Izquierda: Información de la Tradición -->
            <div class="lg:col-span-7 p-8 sm:p-12 md:p-14 flex flex-col justify-center">
                <div class="mb-4">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 text-amber-200 border border-amber-400/30 text-[11px] font-bold uppercase tracking-wider backdrop-blur shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Patrimonio Cultural Inmaterial Colombiano
                    </span>
                </div>
                <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4 text-white drop-shadow-sm">
                    Manos Ancestrales: El Alma de Colombia
                </h1>
                <p class="text-[#F8EAE2] text-sm sm:text-base leading-relaxed mb-8 max-w-xl font-normal">
                    Descubre piezas maestras elaboradas a mano por comunidades Wayuu, Zenú, alfareros de Ráquira y barnizadores de Pasto. Cada compra respalda de forma directa y transparente a los talleres familiares en sus regiones de origen.
                </p>
                <div class="flex flex-wrap gap-4 items-center">
                    <a href="#catalogo" class="px-7 py-3.5 rounded-xl bg-gradient-to-r from-[#D9822B] to-[#E99638] hover:from-[#C27120] hover:to-[#D9822B] text-white text-xs sm:text-sm font-bold shadow-lg shadow-[#D9822B]/35 hover:shadow-xl hover:shadow-[#D9822B]/45 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span>Explorar Catálogo</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <a href="#garantias" class="px-6 py-3.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs sm:text-sm font-semibold border border-white/30 backdrop-blur transition-all flex items-center gap-2">
                        <span>Garantías de Compra</span>
                    </a>
                </div>
            </div>

            <!-- Columna Derecha: Fotografía de Maestría Artesanal -->
            <div class="lg:col-span-5 relative min-h-[280px] lg:min-h-full p-4 lg:p-6 flex items-center justify-center">
                <div class="w-full h-full relative rounded-2xl overflow-hidden shadow-2xl border-2 border-white/20">
                    <img 
                        src="https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?auto=format&fit=crop&w=1000&q=80" 
                        alt="Maestro artesano moldeando vasija de arcilla tradicional en torno" 
                        class="w-full h-full object-cover"
                    />
                    <!-- Gradiente de fusión suave -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#531808]/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 left-3 right-3 bg-black/60 backdrop-blur-md px-3.5 py-2 rounded-xl text-[11px] text-[#FDEBD9] font-medium border border-white/15 flex items-center justify-between">
                        <span>Alfarería Tradicional de Boyacá</span>
                        <span class="text-amber-300 font-bold">100% Hecho a Mano</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Filtros de Región y Barra de Estado -->
    <div id="catalogo" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 pb-3 scroll-mt-24 border-b border-[#DECFC1]">
        <!-- Filtros por Región (Estilo Botones Píldora Cálidos) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            <a href="{{ route('catalog.index') }}#catalogo" class="px-4 py-2 rounded-full border text-xs font-bold whitespace-nowrap transition-all {{ !request('region') ? 'bg-gradient-to-r from-[#8D341B] to-[#A64124] text-white border-[#8D341B] shadow-md shadow-[#8D341B]/25' : 'bg-[#F6EFE7] text-[#5C3828] border-[#DECFC1] hover:bg-[#EFE4D7] hover:border-[#8D341B] hover:text-[#8D341B]' }}">
                Todas las Regiones
            </a>
            @foreach($regions as $reg)
                <a href="{{ route('catalog.index', ['region' => $reg]) }}#catalogo" class="px-4 py-2 rounded-full border text-xs font-semibold whitespace-nowrap transition-all {{ request('region') === $reg ? 'bg-gradient-to-r from-[#8D341B] to-[#A64124] text-white border-[#8D341B] shadow-md shadow-[#8D341B]/25' : 'bg-[#F6EFE7] text-[#5C3828] border-[#DECFC1] hover:bg-[#EFE4D7] hover:border-[#8D341B] hover:text-[#8D341B]' }}">
                    {{ $reg }}
                </a>
            @endforeach
        </div>

        <!-- Contador de Obras Maestras -->
        <div class="text-xs text-[#5C3828] font-semibold bg-[#FAF2EA] px-3.5 py-1.5 rounded-full border border-[#E8DFD3] shrink-0 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-[#D9822B]"></span>
            <span>Mostrando <strong class="text-[#8D341B] font-bold">{{ $products->count() }}</strong> obras maestras</span>
        </div>
    </div>

    <!-- Rejilla de Tarjetas de Artesanías con Sombreado Dinámico y Selección -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="productGrid">
        @forelse($products as $product)
            @php
                $images = $product->gallery_images;
                if (empty($images)) {
                    $images = [$product->image_url ?? 'https://images.unsplash.com/photo-1544816155-12df9643f363'];
                }
                $images = array_slice($images, 0, 3);

                // Colores representativos de cada región artesanal de Colombia
                $regionColors = [
                    'Tuchín, Córdoba' => 'bg-[#5C3E29]/95 text-[#FCEEE3] border-[#8C6445]',
                    'La Guajira, Colombia' => 'bg-[#8D341B]/95 text-[#FFF2EB] border-[#BA5738]',
                    'Ráquira, Boyacá' => 'bg-[#2E2824]/95 text-[#F5EDE4] border-[#5E524A]',
                    'Pitalito, Huila' => 'bg-[#963717]/95 text-[#FFF0E8] border-[#C85D36]',
                    'San Juan de Pasto, Nariño' => 'bg-[#154734]/95 text-[#E0F5EB] border-[#2E7A5A]',
                    'San Jacinto, Bolívar' => 'bg-[#1F3854]/95 text-[#E5F0FC] border-[#3F638E]',
                ];
                $badgeClass = $regionColors[$product->origin_region] ?? 'bg-[#231F1D]/90 text-white border-white/20';
            @endphp

            <article 
                id="card-product-{{ $product->id }}" 
                class="artisan-card flex flex-col group cursor-pointer overflow-hidden" 
                tabindex="0"
                role="button"
                aria-pressed="false"
                onclick="selectProductCard({{ $product->id }}, event)"
                onkeydown="handleCardKeydown(event, {{ $product->id }})"
            >
                <!-- Línea Superior de Acento de Color Artesanal -->
                <div class="h-1.5 w-full bg-gradient-to-r from-[#8D341B] via-[#D9822B] to-[#A34328] opacity-75 group-hover:opacity-100 transition-opacity"></div>

                <!-- Contenedor Visual de Fotografía -->
                <div class="relative h-64 overflow-hidden bg-stone-200/60" id="card-media-{{ $product->id }}">
                    
                    <!-- Imagen Principal con Zoom Suave al Interactuar -->
                    <img 
                        id="product-img-{{ $product->id }}" 
                        src="{{ $images[0] }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />

                    <!-- Indicador Flotante de Selección (Se activa al hacer click en la carta) -->
                    <div id="selected-badge-{{ $product->id }}" class="selected-pill opacity-0 scale-90 transition-all duration-300 absolute top-3 right-3 z-30 pointer-events-none">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-[#8D341B] to-[#6C230E] text-[#FFF9F5] text-[11px] font-bold shadow-lg shadow-[#8D341B]/50 border border-white/40">
                            <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Seleccionada</span>
                        </span>
                    </div>

                    <!-- Badges Superiores con Identidad Regional Enriquecida -->
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                        <span class="px-2.5 py-1 {{ $badgeClass }} backdrop-blur text-[11px] font-semibold rounded-lg tracking-wide flex items-center gap-1.5 shadow-md border">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $product->origin_region }}</span>
                        </span>

                        @if($product->is_fragile)
                            <span class="px-2.5 py-1 bg-[#FFF3ED] text-[#8D341B] border border-[#F4C5B3] text-[10px] font-bold rounded-lg flex items-center gap-1 shadow-sm">
                                <svg class="w-3 h-3 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Pieza Delicada</span>
                            </span>
                        @endif
                    </div>

                    <!-- Indicadores de hasta 3 fotos para piezas con galería -->
                    @if(count($images) > 1)
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full z-20 border border-white/20">
                            @foreach($images as $idx => $imgUrl)
                                <button 
                                    type="button" 
                                    onclick="switchCardImage('{{ $product->id }}', '{{ $imgUrl }}', {{ $idx }}); event.stopPropagation();" 
                                    aria-label="Ver imagen {{ $idx + 1 }} de {{ $product->name }}" 
                                    class="indicator-dot-{{ $product->id }} w-2.5 h-2.5 rounded-full transition-all {{ $idx === 0 ? 'bg-amber-300 w-5' : 'bg-white/60 hover:bg-white' }}"
                                ></button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Stock Disponible -->
                    <div class="absolute bottom-3 right-3 z-10">
                        <span class="px-2.5 py-1 bg-white/95 backdrop-blur text-[#231F1D] text-[10.5px] font-bold rounded-lg shadow-sm border border-stone-200">
                            Disponibles: <strong class="text-[#8D341B] font-mono">{{ $product->stock }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Detalle y Cuerpo de la Artesanía -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <!-- Píldora de Técnica Artesanal con Resalte Cálido -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FAF0EB] border border-[#F2D1C2] text-[#8D341B] text-[10.5px] font-bold uppercase tracking-wider mb-2.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D9822B]"></span>
                            {{ $product->technique }}
                        </span>

                        <h2 class="font-serif font-bold text-xl text-[#231F1D] leading-snug group-hover:text-[#8D341B] transition-colors">
                            {{ $product->name }}
                        </h2>

                        <p class="mt-1.5 text-xs text-[#6E6864] flex items-center gap-1.5">
                            <span class="text-[#D9822B] font-bold text-[10.5px] uppercase tracking-wider">Maestro:</span>
                            <span class="font-semibold text-stone-900">{{ $product->artisan_name }}</span>
                        </p>

                        <p class="mt-3 text-xs text-stone-600 line-clamp-3 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Pie de Tarjeta con Contraste Suave, Precio y Acción -->
                    <div class="mt-6 pt-4 border-t border-[#EAE0D4] bg-gradient-to-r from-[#FDF8F3] to-[#F8EFE5] -mx-6 -mb-6 p-5 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[10px] text-[#7A6A5E] uppercase font-bold tracking-wider block">Precio de Taller</span>
                            <span class="text-lg font-bold text-[#6C230E] font-mono tracking-tight">{{ $product->formatted_price }}</span>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button 
                                type="submit" 
                                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#8D341B] to-[#A83E20] hover:from-[#6C230E] hover:to-[#8D341B] text-white text-xs font-bold shadow-md shadow-[#8D341B]/25 hover:shadow-lg hover:shadow-[#8D341B]/35 transition-all flex items-center gap-1.5 active:scale-95"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Al Carrito</span>
                            </button>
                        </form>
                    </div>
                </div>

            </article>
        @empty
            <div class="col-span-full p-12 bg-white rounded-[16px] border border-[#DECFC1] text-center shadow-sm">
                <p class="text-stone-600 text-sm font-medium">No se encontraron artesanías para los filtros seleccionados.</p>
                <a href="{{ route('catalog.index') }}" class="mt-3 inline-block text-[#8D341B] font-bold text-xs underline">
                    Restablecer filtros del catálogo
                </a>
            </div>
        @endforelse
    </div>

    <!-- Sección de Garantías Oficiales con Gradientes Cálidos e Iconos Vivos -->
    <div id="garantias" class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8 scroll-mt-24">
        
        <div class="p-6 rounded-[16px] bg-gradient-to-br from-white via-[#FFFDF9] to-[#FAF3EB] border border-[#E8DDD0] shadow-sm hover:shadow-md transition-all space-y-3.5">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#FBECE4] to-[#F5D8CC] text-[#8D341B] border border-[#ECC1AF] flex items-center justify-center shadow-xs">
                <!-- Icono de Trato Directo / Comercio Justo -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="font-serif font-bold text-lg text-[#231F1D]">Comercio Justo y Directo</h3>
            <p class="text-xs text-[#5C4A3E] leading-relaxed">
                Sin intermediarios abusivos. El 100% del valor pactado llega directamente a las manos de los maestros artesanos y sus asociaciones comunitarias en cada región.
            </p>
        </div>

        <div class="p-6 rounded-[16px] bg-gradient-to-br from-white via-[#FFFDF9] to-[#FAF3EB] border border-[#E8DDD0] shadow-sm hover:shadow-md transition-all space-y-3.5">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#FEF4E8] to-[#FCE3C5] text-[#D9822B] border border-[#FAD3A7] flex items-center justify-center shadow-xs">
                <!-- Icono de Protección y Embalaje Seguro -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="font-serif font-bold text-lg text-[#231F1D]">Protección en el Envío</h3>
            <p class="text-xs text-[#5C4A3E] leading-relaxed">
                Embalaje acolchado de grado exportación y seguro de rotura opcional para piezas frágiles y cerámicas durante su transporte nacional.
            </p>
        </div>

        <div class="p-6 rounded-[16px] bg-gradient-to-br from-white via-[#FFFDF9] to-[#FAF3EB] border border-[#E8DDD0] shadow-sm hover:shadow-md transition-all space-y-3.5">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#EAF5EF] to-[#D5EDE0] text-[#1E5C3D] border border-[#BCE3CE] flex items-center justify-center shadow-xs">
                <!-- Icono de Certificado de Autenticidad -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <h3 class="font-serif font-bold text-lg text-[#231F1D]">Certificado de Autenticidad</h3>
            <p class="text-xs text-[#5C4A3E] leading-relaxed">
                Cada obra incluye su comprobante cultural con el nombre del maestro artesano, la técnica ancestral y la denominación de origen territorial.
            </p>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // Selector dinámico de fotos de la tarjeta
    function switchCardImage(productId, imageUrl, activeIndex) {
        const img = document.getElementById('product-img-' + productId);
        if (img) {
            img.src = imageUrl;
        }

        const dots = document.querySelectorAll('.indicator-dot-' + productId);
        dots.forEach((dot, idx) => {
            if (idx === activeIndex) {
                dot.classList.add('bg-amber-300', 'w-5');
                dot.classList.remove('bg-white/60');
            } else {
                dot.classList.remove('bg-amber-300', 'w-5');
                dot.classList.add('bg-white/60');
            }
        });
    }

    // Función interactiva de Selección de Cartas con Sombreado Elevado
    function selectProductCard(productId, event) {
        // Evitar activar la selección si el click provino de los botones del carrusel o del botón "+ Al Carrito"
        if (event && event.target.closest('button, form, a, input')) {
            return;
        }

        const targetCard = document.getElementById('card-product-' + productId);
        if (!targetCard) return;

        const allCards = document.querySelectorAll('.artisan-card');
        const isAlreadySelected = targetCard.classList.contains('is-selected');

        // Limpiar estado seleccionado en las demás tarjetas
        allCards.forEach(card => {
            card.classList.remove('is-selected');
            card.setAttribute('aria-pressed', 'false');
        });

        // Conmutar estado de la tarjeta clickeada
        if (!isAlreadySelected) {
            targetCard.classList.add('is-selected');
            targetCard.setAttribute('aria-pressed', 'true');
        }
    }

    // Soporte para navegación accesible por teclado (Enter o Espacio)
    function handleCardKeydown(event, productId) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            selectProductCard(productId, event);
        }
    }
</script>
@endsection
