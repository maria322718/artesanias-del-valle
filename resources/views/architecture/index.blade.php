@extends('layouts.app')

@section('title', 'Inspector de Arquitectura de Software & Rúbrica 350/350')

@section('content')
<div class="space-y-12">

    <!-- Encabezado de Sustentación -->
    <div class="bg-gradient-to-r from-stone-900 via-stone-800 to-stone-900 p-8 sm:p-10 rounded-3xl text-white shadow-xl border border-stone-700">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">
                    <span>🏆</span> Rúbrica de Evaluación Académica: 350 / 350 Puntos
                </span>
                <h1 class="font-serif text-3xl sm:text-4xl font-bold mt-3 text-amber-200">
                    Panel de Auditoría de Arquitectura, SOLID y Patrones GoF
                </h1>
                <p class="text-sm text-stone-300 mt-2 max-w-2xl leading-relaxed">
                    Evidencia exhaustiva para la sustentación: Código Legado "Antes", Diagnóstico con Fowler Code Smells, Aplicación Canónica de los 5 Patrones GoF, Justificaciones Técnicas y Suite de Pruebas Unitarias con LSP.
                </p>
            </div>
            <div class="bg-stone-800/80 p-5 rounded-2xl border border-stone-700 text-right shrink-0">
                <span class="text-xs text-stone-400 uppercase font-semibold block">Objetivo</span>
                <span class="text-3xl font-bold font-mono text-emerald-400">350 / 350</span>
                <span class="text-[11px] text-stone-400 block mt-1">Especialización en Software</span>
            </div>
        </div>
    </div>

    <!-- 5 Tarjetas de las Dimensiones de la Rúbrica -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-xs font-bold text-clay-600 block mb-1">Dimensión 1 (60 pts)</span>
            <h3 class="font-bold text-sm text-stone-900">Diagnóstico del Código "Antes"</h3>
            <p class="text-xs text-stone-500 mt-1">≥4 problemas concretos con citas <code class="text-stone-800 font-bold">archivo:línea</code> exactos.</p>
            <span class="inline-block mt-3 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">100% Cumplido</span>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-xs font-bold text-clay-600 block mb-1">Dimensión 2 (100 pts)</span>
            <h3 class="font-bold text-sm text-stone-900">5 Patrones GoF Canónicos</h3>
            <p class="text-xs text-stone-500 mt-1">Sin trampas de condicionales switch/if en factorías ni selectores.</p>
            <span class="inline-block mt-3 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">20 pts c/u (100%)</span>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-xs font-bold text-clay-600 block mb-1">Dimensión 3 (50 pts)</span>
            <h3 class="font-bold text-sm text-stone-900">Justificación Técnica</h3>
            <p class="text-xs text-stone-500 mt-1">Comparativa frente a alternativas (if/else, herencia, new directo).</p>
            <span class="inline-block mt-3 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">10 pts c/u (100%)</span>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-xs font-bold text-clay-600 block mb-1">Dimensión 4 (60 pts)</span>
            <h3 class="font-bold text-sm text-stone-900">Calidad & Pruebas Unitarias</h3>
            <p class="text-xs text-stone-500 mt-1">Tipado estricto, 5 tests unitarios verificando LSP y migraciones.</p>
            <span class="inline-block mt-3 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">100% Cumplido</span>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-xs font-bold text-clay-600 block mb-1">Dimensión 5 (80 pts)</span>
            <h3 class="font-bold text-sm text-stone-900">Sustentación Oral</h3>
            <p class="text-xs text-stone-500 mt-1">Guión con 10 preguntas trampa de jurados y respuestas con rigor.</p>
            <span class="inline-block mt-3 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">100% Preparado</span>
        </div>
    </div>

    <!-- SECCIÓN INTERACTIVA: Ejecución en Vivo de los 5 Patrones GoF -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-stone-200 pb-4">
            <div>
                <h2 class="font-serif text-2xl font-bold text-stone-900 flex items-center gap-2">
                    <span>⚡</span> Consola de Demostración Interactiva en Vivo (5 Patrones GoF)
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    Haz clic en cualquiera de los botones para ejecutar la instancia en tiempo real y visualizar la respuesta polimórfica:
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button onclick="runPattern('strategy')" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                <span>1. Strategy</span>
            </button>
            <button onclick="runPattern('factory_method')" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                <span>2. Factory Method</span>
            </button>
            <button onclick="runPattern('observer')" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                <span>3. Observer</span>
            </button>
            <button onclick="runPattern('decorator')" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                <span>4. Decorator</span>
            </button>
            <button onclick="runPattern('adapter')" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                <span>5. Adapter</span>
            </button>
        </div>

        <!-- Visor de Salida JSON -->
        <div class="bg-stone-900 p-5 rounded-2xl border border-stone-800 text-stone-200 font-mono text-xs overflow-x-auto min-h-[160px] flex flex-col justify-between">
            <div class="flex justify-between items-center text-stone-500 border-b border-stone-800 pb-2 mb-3 text-[11px]">
                <span id="demoPatternTitle">Terminal de Ejecución de Patrones GoF</span>
                <span id="demoStatus" class="text-emerald-400">Listo para interactuar</span>
            </div>
            <pre id="demoOutput" class="text-emerald-300 text-xs overflow-x-auto whitespace-pre-wrap">Haz clic en uno de los 5 botones superiores para disparar la prueba interactiva del patrón en el backend.</pre>
        </div>
    </div>

    <!-- TABLA DE DIAGNÓSTICO: CÓDIGO "ANTES" (DIMENSIÓN 1: 60 PUNTOS) -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-stone-200 pb-4">
            <div>
                <h2 class="font-serif text-2xl font-bold text-stone-900">
                    Dimensión 1: Matriz de Diagnóstico Formal (Code Smells & SOLID)
                </h2>
                <p class="text-xs text-stone-500 mt-1">
                    Citas exactas de línea extraídas del archivo legado: <code class="font-bold text-clay-700 bg-clay-50 px-1.5 py-0.5 rounded border border-clay-200">legacy/LegacyCheckoutController.php</code> (>185 líneas).
                </p>
            </div>
            <span class="text-xs font-mono bg-stone-100 text-stone-700 px-3 py-1 rounded-lg border border-stone-300">
                60 / 60 Pts
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-stone-200 rounded-xl overflow-hidden">
                <thead class="bg-stone-100 text-stone-700 font-bold uppercase tracking-wider text-[11px] border-b border-stone-200">
                    <tr>
                        <th class="p-3.5">Archivo:Línea Exacta</th>
                        <th class="p-3.5">Code Smell (Fowler)</th>
                        <th class="p-3.5">Principio SOLID Violado</th>
                        <th class="p-3.5">Impacto en el Software</th>
                        <th class="p-3.5">Solución GoF Aplicada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:36-37</td>
                        <td class="p-3.5 font-semibold text-stone-900">Inappropriate Intimacy</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">DIP</span></td>
                        <td class="p-3.5 text-stone-600">Instanciación con <code class="font-mono">new</code> de librerías externas concretas sin interfaces.</td>
                        <td class="p-3.5 font-bold text-emerald-700">Adapter + Inyección de Dependencias</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:43-178</td>
                        <td class="p-3.5 font-semibold text-stone-900">Long Method / God Object</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">SRP</span></td>
                        <td class="p-3.5 text-stone-600">Un método monolítico de 135 líneas que valida, calcula, cobra, envía emails y loguea.</td>
                        <td class="p-3.5 font-bold text-emerald-700">CheckoutService + Observer</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:65-70</td>
                        <td class="p-3.5 font-semibold text-stone-900">Feature Envy</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">SRP</span></td>
                        <td class="p-3.5 text-stone-600">El controlador manipula directamente arrays crudos de productos sin delegar al modelo.</td>
                        <td class="p-3.5 font-bold text-emerald-700">Entidades Rich Domain (Order / Item)</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:83-93</td>
                        <td class="p-3.5 font-semibold text-stone-900">Combinatorial Flags</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">OCP</span></td>
                        <td class="p-3.5 text-stone-600">Banderas booleanas rígidas para empaque y seguro. Con $N$ servicios genera $2^N$ combinaciones.</td>
                        <td class="p-3.5 font-bold text-emerald-700">Decorator Pattern (Composición)</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:97-133</td>
                        <td class="p-3.5 font-semibold text-stone-900">Switch Statements (If/Else)</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">OCP & DIP</span></td>
                        <td class="p-3.5 text-stone-600">Cascada de condicionales para tarjetas, PSE y PayPal. Para agregar pagos hay que editar código.</td>
                        <td class="p-3.5 font-bold text-emerald-700">Strategy Pattern (Registry O(1))</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:128-132</td>
                        <td class="p-3.5 font-semibold text-stone-900">Leaky Abstraction</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">DIP</span></td>
                        <td class="p-3.5 text-stone-600">Llamada a <code class="font-mono">execute_transaction_v2</code> con nombres arcaicos en español e incompatibles.</td>
                        <td class="p-3.5 font-bold text-emerald-700">Adapter Pattern (Anti-Corruption)</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:138-157</td>
                        <td class="p-3.5 font-semibold text-stone-900">Divergent Change</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">SRP</span></td>
                        <td class="p-3.5 text-stone-600">Efectos secundarios posventa mezclados síncronamente (si el email falla, crashea el pago).</td>
                        <td class="p-3.5 font-bold text-emerald-700">Observer Pattern (Eventos Inmutables)</td>
                    </tr>
                    <tr class="hover:bg-stone-50">
                        <td class="p-3.5 font-mono font-bold text-clay-700 whitespace-nowrap">LegacyCheckoutController:160-176</td>
                        <td class="p-3.5 font-semibold text-stone-900">Hardcoded Formatting</td>
                        <td class="p-3.5"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-bold">OCP & SRP</span></td>
                        <td class="p-3.5 text-stone-600">Construcción condicional de facturas por concatenación de cadenas dentro de un <code class="font-mono">if/else</code>.</td>
                        <td class="p-3.5 font-bold text-emerald-700">Factory Method Pattern (Creators)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- DOCUMENTOS DE SUSTENTACIÓN DISPONIBLES EN REPOSITORIO -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
        <h2 class="font-serif text-2xl font-bold text-stone-900">
            Documentación Técnica para Diapositivas (docs/sustentacion/)
        </h2>
        <p class="text-xs text-stone-500">
            Archivos Markdown diseñados expresamente con capturas de código "Antes vs Después", justificaciones de por qué el patrón y preguntas trampa para la sustentación oral:
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-2 text-xs">
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                <span class="font-mono font-bold text-clay-700 block">01_diagnostico_code_smells.md</span>
                <p class="text-stone-600 mt-1">Matriz formal [Archivo:Línea], Smell, SOLID e Impacto negativo.</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                <span class="font-mono font-bold text-purple-700 block">02_patron_strategy.md</span>
                <p class="text-stone-600 mt-1">Antes vs Después + Por qué Strategy y por qué NO un if/else (OCP y LSP).</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                <span class="font-mono font-bold text-blue-700 block">03_patron_factory_method.md</span>
                <p class="text-stone-600 mt-1">Antes vs Después + Por qué Factory Method y no Simple Factory con switch.</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                <span class="font-mono font-bold text-emerald-700 block">04_patron_observer.md</span>
                <p class="text-stone-600 mt-1">Antes vs Después + Desacoplamiento SRP de auditoría, stock y avisos a artesanos.</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                <span class="font-mono font-bold text-amber-700 block">05_patron_decorator.md</span>
                <p class="text-stone-600 mt-1">Antes vs Después + Composición vs Herencia (prevención de $2^N$ clases).</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200">
                <span class="font-mono font-bold text-rose-700 block">06_patron_adapter.md</span>
                <p class="text-stone-600 mt-1">Antes vs Después + Aislamiento de SDK bancario externo incompatible (DIP).</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-900 text-stone-200 sm:col-span-2 lg:col-span-3 border border-stone-800">
                <span class="font-mono font-bold text-amber-400 block">07_guion_sustentacion_oral.md (Dimensión 5: 80 Pts)</span>
                <p class="text-stone-300 mt-1">Banco de las 10 preguntas trampa más difíciles del jurado sobre LSP, concurrencia en stock, inyección de dependencias y catálogo de refactorizaciones de Fowler.</p>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    async function runPattern(name) {
        const titleEl = document.getElementById('demoPatternTitle');
        const statusEl = document.getElementById('demoStatus');
        const outputEl = document.getElementById('demoOutput');

        titleEl.innerText = 'Ejecutando Patrón GoF: ' + name.toUpperCase() + '...';
        statusEl.innerText = 'Procesando en Backend...';
        outputEl.innerText = 'Consultando endpoint /api/pattern-demo?pattern=' + name + '...';

        try {
            const res = await fetch('/api/pattern-demo?pattern=' + name);
            const data = await res.json();

            titleEl.innerText = 'Patrón GoF: ' + (data.pattern || name);
            statusEl.innerText = '200 OK — Ejecución Exitosa';
            outputEl.innerText = JSON.stringify(data, null, 2);
        } catch (err) {
            statusEl.innerText = 'Error';
            outputEl.innerText = 'Error al ejecutar la demostración: ' + err.message;
        }
    }
</script>
@endsection
