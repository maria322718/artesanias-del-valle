<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Artesanías del Valle — Tienda Oficial de Artesanías Colombianas')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN con Configuración Personalizada -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        terracota: {
                            DEFAULT: '#8D341B',
                            dark: '#6C230E',
                            light: '#A34328',
                            50: '#FDF7F4',
                            100: '#F8EBE4',
                            200: '#EFCFBF',
                            600: '#8D341B',
                            700: '#6C230E',
                        },
                        ocre: {
                            DEFAULT: '#D9822B',
                            hover: '#C27120',
                            light: '#F8E9D8',
                            gold: '#F59E0B',
                        },
                        craft: {
                            bg: '#F8F4EE',
                            warm: '#F3ECE1',
                            card: '#FFFFFF',
                            text: '#231F1D',
                            muted: '#6E6864',
                            border: '#E5DDD2',
                        }
                    },
                    borderRadius: {
                        'craft': '16px',
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --terracota: #8D341B;
            --terracota-dark: #6C230E;
            --terracota-light: #A34328;
            --ocre: #D9822B;
            --ocre-light: #F59E0B;
            --bg: #F8F4EE;
            --bg-warm: #F2E9DE;
            --card-bg: #FFFFFF;
            --text: #231F1D;
            --muted: #6E6864;
            --border: #E5DDD2;
            --radius: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            background-image: 
                radial-gradient(at 15% 15%, rgba(217, 130, 43, 0.07) 0px, transparent 45%),
                radial-gradient(at 85% 85%, rgba(141, 52, 27, 0.08) 0px, transparent 45%),
                radial-gradient(at 50% 40%, rgba(108, 35, 14, 0.04) 0px, transparent 55%);
            background-attachment: fixed;
        }

        .font-editorial {
            font-family: 'Playfair Display', serif;
        }

        .border-craft {
            border-color: var(--border);
        }

        /* Sistema de Tarjetas Artesanales con Sombreado Dinámico y Selección */
        .artisan-card {
            background: linear-gradient(180deg, #FFFFFF 0%, #FDFBF8 100%);
            border: 1.5px solid #E6DED2;
            border-radius: var(--radius);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            box-shadow: 0 4px 10px -2px rgba(108, 35, 14, 0.06), 0 2px 4px -2px rgba(108, 35, 14, 0.04);
        }

        /* Sombreado Elevado al Pasar el Cursor (Hover) */
        .artisan-card:hover {
            transform: translateY(-6px);
            border-color: #D9822B;
            box-shadow: 0 22px 30px -8px rgba(141, 52, 27, 0.22), 0 8px 16px -4px rgba(217, 130, 43, 0.16);
        }

        /* Sombreado Destacado y Aura Cálida al Seleccionar (Click / Focus) */
        .artisan-card.is-selected {
            transform: translateY(-8px) scale(1.012);
            border-color: #8D341B !important;
            box-shadow: 
                0 0 0 3px rgba(141, 52, 27, 0.35),
                0 28px 40px -10px rgba(108, 35, 14, 0.32),
                0 14px 22px -6px rgba(217, 130, 43, 0.24) !important;
            background: linear-gradient(180deg, #FFFFFF 0%, #FFF9F5 100%) !important;
        }

        .artisan-card:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px #D9822B, 0 20px 25px -5px rgba(141, 52, 27, 0.2);
        }

        .artisan-card.is-selected .selected-pill {
            opacity: 1 !important;
            transform: scale(1) !important;
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans selection:bg-terracota selection:text-white">

    <!-- Barra de Anuncio Oficial con Gradiente Cálido y Tipografía Dorada -->
    <div class="bg-gradient-to-r from-[#5B1C0A] via-[#6C230E] to-[#7D2911] text-[#FDEBD9] text-xs py-2 px-4 text-center font-semibold tracking-wide border-b border-[#8D341B]/40 shadow-sm flex items-center justify-center gap-2">
        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse inline-block"></span>
        <span>Comercio Justo Directo con Comunidades Artesanales de Colombia &bull; La Guajira, Boyacá, Córdoba, Huila, Nariño y Bolívar</span>
    </div>

    <!-- Navegación Principal con Borde de Acento Cálido -->
    <header class="sticky top-0 z-50 bg-[#FFFCF9]/95 backdrop-blur border-b border-[#E5DDD2] shadow-[0_2px_12px_rgba(108,35,14,0.04)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[75px]">
                
                <!-- Logotipo Artesanal Nuevo -->
                <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3.5 group">
                    <div class="w-11 h-11 rounded-xl bg-[#8D341B] flex items-center justify-center text-white shadow-sm group-hover:bg-[#6C230E] transition-colors p-2">
                        <!-- Emblema de Vasija de Barro Ancestral y Sol -->
                        <svg class="w-full h-full text-white" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 10h20M18 10c0 4-4 8-4 14 0 7 4 12 10 12s10-5 10-12c0-6-4-10-4-14" />
                            <ellipse cx="24" cy="10" rx="10" ry="3" />
                            <path d="M12 20c-3 0-5 2-5 5 0 3 2 5 5 5" />
                            <path d="M36 20c3 0 5 2 5 5 0 3-2 5-5 5" />
                            <circle cx="24" cy="24" r="3" fill="currentColor" />
                            <path d="M19 32c1.5 1.5 3.2 2.5 5 2.5s3.5-1 5-2.5" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-serif font-bold text-xl sm:text-2xl text-[#8D341B] block leading-tight tracking-tight">
                            Artesanías del Valle
                        </span>
                        <span class="text-[10px] text-[#6E6864] tracking-widest uppercase font-semibold block">
                            Tradición &bull; Maestría Ancestral
                        </span>
                    </div>
                </a>

                <!-- Menú de Navegación -->
                <nav class="hidden md:flex items-center space-x-7 text-sm font-medium">
                    <a href="{{ route('catalog.index') }}" class="transition-colors {{ request()->routeIs('catalog.*') ? 'text-[#8D341B] font-bold' : 'text-[#231F1D] hover:text-[#8D341B]' }}">
                        Catálogo de Maestros
                    </a>
                    <a href="{{ route('catalog.index') }}#regiones" class="text-[#231F1D] hover:text-[#8D341B] transition-colors">
                        Regiones y Tradición
                    </a>
                    <a href="{{ route('catalog.index') }}#garantias" class="text-[#231F1D] hover:text-[#8D341B] transition-colors">
                        Comercio Justo
                    </a>
                    <a href="{{ route('orders.tracking') }}" class="transition-colors {{ request()->routeIs('orders.*') ? 'text-[#8D341B] font-bold' : 'text-[#231F1D] hover:text-[#8D341B]' }} flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#FAF2EA] border border-[#E8DFD3] hover:border-[#8D341B] text-xs">
                        <svg class="w-3.5 h-3.5 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Consultar Pedido</span>
                    </a>
                </nav>

                <!-- Botón de Carrito (Estilo Cápsula) -->
                <div class="flex items-center space-x-4">
                    @php
                        $cartCount = count(session('cart', []));
                    @endphp
                    <a href="{{ route('cart.index') }}" class="px-4 py-2 rounded-full border border-[#E8E2D9] bg-white text-[#231F1D] hover:bg-[#FBF9F5] transition flex items-center gap-2.5 text-xs font-semibold shadow-sm">
                        <svg class="w-4 h-4 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Mi Carrito ({{ $cartCount }})</span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- Alertas Flash -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
        @if(session('success'))
            <div class="p-4 rounded-[14px] bg-white border border-emerald-300 text-emerald-900 text-xs font-medium flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-[14px] bg-white border border-red-300 text-red-900 text-xs font-medium flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 rounded-[14px] bg-white border border-amber-300 text-amber-900 text-xs font-medium flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>{{ session('info') }}</div>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer E-commerce Profesional (Sin Emojis) -->
    <footer class="bg-[#191615] text-[#C2BBB5] border-t border-stone-800 mt-20 pt-14 pb-8 text-[0.88rem]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10 border-b border-stone-800">
                
                <!-- Columna 1: Identidad y Misión -->
                <div class="space-y-3.5">
                    <h4 class="text-white font-serif text-lg font-bold">
                        Artesanías del Valle
                    </h4>
                    <p class="text-xs text-stone-400 leading-relaxed max-w-sm">
                        Preservamos, difundimos y comercializamos piezas maestras elaboradas 100% a mano por comunidades y talleres tradicionales de Colombia. Respaldamos el comercio justo y directo en cada territorio de origen.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-2 text-[11px] text-stone-300 font-medium">
                        <span class="px-2.5 py-1 rounded bg-stone-800 border border-stone-700">Hecho a Mano</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 border border-stone-700">Comercio Justo</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 border border-stone-700">Envíos Directos</span>
                    </div>
                </div>

                <!-- Columna 2: Regiones & Saberes -->
                <div class="space-y-3">
                    <h4 class="text-white font-serif text-base font-bold">
                        Regiones & Saberes
                    </h4>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li>&bull; Tuchín, Córdoba &mdash; Caña Flecha</li>
                        <li>&bull; La Guajira &mdash; Tejido Wayuu</li>
                        <li>&bull; Ráquira, Boyacá &mdash; Alfarería Negra</li>
                        <li>&bull; Pitalito, Huila &mdash; Chivas en Barro</li>
                        <li>&bull; San Juan de Pasto, Nariño &mdash; Barniz Mopa-Mopa</li>
                        <li>&bull; San Jacinto, Bolívar &mdash; Telar Vertical</li>
                    </ul>
                </div>

                <!-- Columna 3: Garantías -->
                <div class="space-y-3">
                    <h4 class="text-white font-serif text-base font-bold">
                        Garantías & Compras
                    </h4>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li>&bull; <a href="{{ route('orders.tracking') }}" class="text-amber-400 hover:underline font-semibold flex items-center gap-1"><span>Consultar estado de pedido con NIT</span> &rarr;</a></li>
                        <li>&bull; Embalaje protector especial para piezas delicadas</li>
                        <li>&bull; Envíos directos desde las regiones de origen</li>
                        <li>&bull; Comercio justo comprobable con artesanos</li>
                        <li>&bull; Facturación electrónica oficial DIAN</li>
                        <li>&bull; Medios de pago seguros: PSE, Tarjetas y Bancos</li>
                    </ul>
                </div>

            </div>

            <!-- Fila Inferior de Derechos -->
            <div class="pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-stone-500 gap-3">
                <p>&copy; {{ date('Y') }} Artesanías del Valle. Tienda Oficial de Artesanías de Colombia.</p>
                <p class="text-stone-400">Comercio Justo &middot; Patrimonio Cultural Inmaterial</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
