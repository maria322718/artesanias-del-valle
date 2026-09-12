<!DOCTYPE html>
<html lang="es" class="h-full bg-stone-50 text-stone-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Artesanías del Valle — Sabiduría Ancestral Colombiana')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        clay: {
                            50: '#FAF5F2',
                            100: '#F5EAE4',
                            200: '#EBD4C9',
                            500: '#C85A32',
                            600: '#B34726',
                            700: '#91351A',
                            800: '#752A15',
                            900: '#5F2212',
                        },
                        emerald: {
                            850: '#03542B',
                            950: '#012915',
                        },
                        sand: {
                            50: '#FDFCFA',
                            100: '#F9F6F0',
                            200: '#F1ECE0',
                            300: '#E3DAC9',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .artisan-pattern {
            background-color: #fbf9f5;
            background-image: radial-gradient(#c85a32 0.75px, transparent 0.75px), radial-gradient(#046a38 0.75px, #fbf9f5 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
            background-opacity: 0.05;
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans selection:bg-clay-500 selection:text-white artisan-pattern">

    <!-- Barra de Anuncio Cultural -->
    <div class="bg-clay-700 text-amber-100 text-xs py-2 px-4 text-center font-medium tracking-wide flex items-center justify-center space-x-2">
        <span>✨ Comercio Justo Directo con Comunidades Artesanales de Colombia: La Guajira, Boyacá, Córdoba, Huila, Nariño y Bolívar</span>
    </div>

    <!-- Navegación Principal -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-stone-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logotipo -->
                <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-clay-700 to-clay-500 flex items-center justify-center text-white shadow-md group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-serif font-bold text-2xl tracking-tight text-stone-900 block leading-tight">Artesanías del Valle</span>
                        <span class="text-[11px] text-stone-500 tracking-wider uppercase font-semibold">Tradición &middot; SOLID &middot; Patrones GoF</span>
                    </div>
                </a>

                <!-- Enlaces de Navegación -->
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('catalog.index') }}" class="text-stone-700 hover:text-clay-600 transition-colors {{ request()->routeIs('catalog.*') ? 'text-clay-600 font-bold border-b-2 border-clay-600 pb-1' : '' }}">
                        Catálogo de Maestros
                    </a>
                    <a href="{{ route('architecture.index') }}" class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 transition">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Inspector Rúbrica 350 / GoF</span>
                    </a>
                </nav>

                <!-- Carrito y Acciones -->
                <div class="flex items-center space-x-4">
                    @php
                        $cartCount = count(session('cart', []));
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl bg-stone-100 hover:bg-clay-50 text-stone-700 hover:text-clay-600 transition flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-clay-600 text-white rounded-full text-xs font-bold flex items-center justify-center shadow">
                                {{ $cartCount }}
                            </span>
                        @endif
                        <span class="hidden sm:inline text-xs font-semibold">Mi Carrito</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Alertas Flash -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div class="font-medium">{{ session('info') }}</div>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer Cultural y Técnico -->
    <footer class="bg-stone-900 text-stone-300 border-t border-stone-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Columna 1: Filosofía -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 text-white font-serif font-bold text-xl mb-3">
                        <span>Artesanías del Valle</span>
                    </div>
                    <p class="text-sm text-stone-400 leading-relaxed max-w-md">
                        Plataforma de comercio electrónico diseñada para salvaguardar y visibilizar las técnicas ancestrales de los maestros artesanos colombianos, construida sobre una arquitectura de software limpia aplicando los 5 principios SOLID y 5 patrones GoF canónicos.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-clay-400 font-semibold border border-stone-700">Strategy</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-clay-400 font-semibold border border-stone-700">Factory Method</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-clay-400 font-semibold border border-stone-700">Observer</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-clay-400 font-semibold border border-stone-700">Decorator</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-clay-400 font-semibold border border-stone-700">Adapter</span>
                    </div>
                </div>

                <!-- Columna 2: Regiones Artesanales -->
                <div>
                    <h4 class="text-xs font-bold tracking-wider text-amber-400 uppercase mb-3">Regiones & Saberes</h4>
                    <ul class="space-y-1.5 text-xs text-stone-400">
                        <li>🌾 Tuchín, Córdoba — Caña Flecha</li>
                        <li>🧶 La Guajira — Tejido Wayuu</li>
                        <li>🏺 Ráquira, Boyacá — Alfarería Negra</li>
                        <li>🚌 Pitalito, Huila — Chivas en Barro</li>
                        <li>🌿 Pasto, Nariño — Barniz Mopa-Mopa</li>
                        <li>🕸️ San Jacinto, Bolívar — Telar Vertical</li>
                    </ul>
                </div>

                <!-- Columna 3: Arquitectura & Rúbrica -->
                <div>
                    <h4 class="text-xs font-bold tracking-wider text-emerald-400 uppercase mb-3">Proyecto Integrador</h4>
                    <ul class="space-y-1.5 text-xs text-stone-400">
                        <li><a href="{{ route('architecture.index') }}" class="hover:text-white transition">Rúbrica de Evaluación (350/350)</a></li>
                        <li><span class="text-stone-500">legacy/LegacyCheckoutController.php</span></li>
                        <li><span class="text-stone-500">docs/sustentacion/01_diagnostico.md</span></li>
                        <li><span class="text-stone-500">tests/Unit/ (5 Tests GoF + LSP)</span></li>
                        <li><span class="text-emerald-500 font-bold">Docker Compose (Sail) + PostgreSQL</span></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-stone-800 flex flex-col sm:flex-row justify-between items-center text-xs text-stone-500">
                <p>&copy; {{ date('Y') }} Artesanías del Valle. Proyecto de Especialización en Arquitectura de Software.</p>
                <p class="mt-2 sm:mt-0 font-mono text-[11px]">PHP 8.2+ &middot; Laravel 11 &middot; PostgreSQL 15 &middot; Docker</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
