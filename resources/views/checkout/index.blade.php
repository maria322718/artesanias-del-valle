@extends('layouts.app')

@section('title', 'Finalizar Compra — Checkout con Patrones GoF y SOLID')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <div class="border-b border-stone-200 pb-4">
        <span class="px-3 py-1 bg-clay-100 text-clay-800 text-xs font-bold rounded-full uppercase tracking-wider">
            Arquitectura Limpia &middot; 5 Patrones GoF Activos
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 mt-2">Checkout Interactivo de Artesanías</h1>
        <p class="text-sm text-stone-500 mt-1">Configura decoradores estructurales, selecciona tu estrategia de pago y define el tipo de comprobante.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Columna Izquierda: Formularios y Selectores (2 Cols) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- 1. Datos del Cliente -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">1</span>
                        <span>Información del Comprador & Despacho</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Nombre Completo *</label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', 'Camila Benavides') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 focus:ring-2 focus:ring-clay-500 focus:border-clay-500 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Correo Electrónico *</label>
                            <input type="email" name="customer_email" required value="{{ old('customer_email', 'camilabenavides@gmail.com') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 focus:ring-2 focus:ring-clay-500 focus:border-clay-500 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Teléfono Móvil / WhatsApp *</label>
                            <input type="text" name="customer_phone" required value="{{ old('customer_phone', '3158889922') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 focus:ring-2 focus:ring-clay-500 focus:border-clay-500 text-sm">
                        </div>
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Dirección de Entrega en Colombia *</label>
                            <input type="text" name="shipping_address" required value="{{ old('shipping_address', 'Carrera 7 # 71-21, Apto 502') }}" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 focus:ring-2 focus:ring-clay-500 focus:border-clay-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- 2. PATRÓN DECORATOR: Servicios de Valor Agregado -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">2</span>
                            <span>Servicios Adicionales (Patrón Decorator)</span>
                        </h2>
                        <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                            Composición Dinámica en Runtime
                        </span>
                    </div>
                    <p class="text-xs text-stone-500">
                        Cada servicio seleccionado envuelve el cálculo base implementando <code class="bg-stone-100 px-1 py-0.5 rounded text-stone-700 font-mono">OrderCostInterface</code> sin alterar la clase original ni recurrir a herencia estática ($2^N$ clases).
                    </p>

                    <div class="space-y-3 pt-2">
                        <!-- Decorador 1: Empaque de mimbre -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border border-stone-200 hover:border-clay-300 cursor-pointer transition bg-stone-50/50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-500">
                            <input type="checkbox" name="gift_wrap" value="1" id="giftWrapCheck" class="mt-1 w-4 h-4 text-clay-600 rounded border-stone-300 focus:ring-clay-500" onchange="updateTotal()">
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-stone-900 text-sm">🧺 Empaque de Regalo Ecológico en Mimbre</span>
                                    <span class="font-mono font-bold text-clay-700 text-sm">+${{ number_format($giftWrapCost, 0, ',', '.') }} COP</span>
                                </div>
                                <p class="text-stone-500 mt-0.5">Canasto artesanal tejido a mano en fibra natural de plátano con lazo de fique biodegradable. <em>(GiftWrapDecorator)</em></p>
                            </div>
                        </label>

                        <!-- Decorador 2: Seguro de rotura -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border border-stone-200 hover:border-clay-300 cursor-pointer transition bg-stone-50/50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-500">
                            <input type="checkbox" name="artisan_insurance" value="1" id="insuranceCheck" {{ $hasFragile ? 'checked' : '' }} class="mt-1 w-4 h-4 text-clay-600 rounded border-stone-300 focus:ring-clay-500" onchange="updateTotal()">
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-stone-900 text-sm">🛡️ Seguro contra Rotura de Piezas Frágiles</span>
                                    <span class="font-mono font-bold text-clay-700 text-sm">+${{ number_format($insuranceCost, 0, ',', '.') }} COP (5%)</span>
                                </div>
                                <p class="text-stone-500 mt-0.5">Reposición 100% garantizada ante accidentes en transporte terrestre para cerámicas y piezas delicadas. <em>(ArtisanInsuranceDecorator)</em></p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. PATRÓN STRATEGY & ADAPTER: Métodos de Pago Desacoplados -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">3</span>
                            <span>Estrategia de Pago (Patrón Strategy & Adapter)</span>
                        </h2>
                        <span class="text-[11px] font-bold text-purple-800 bg-purple-50 px-2.5 py-1 rounded-md border border-purple-200">
                            Polimorfismo sin switch/case
                        </span>
                    </div>
                    <p class="text-xs text-stone-500">
                        Cada pasarela implementa <code class="bg-stone-100 px-1 py-0.5 rounded text-stone-700 font-mono">PaymentStrategyInterface</code>. El registro resuelve la estrategia en $O(1)$ sin condicionales de tipo. La opción bancaria tradicional utiliza además el patrón <strong>Adapter</strong>.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <!-- Strategy 1: Tarjeta -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="credit_card" checked class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('cc')">
                                <span class="font-bold text-sm text-stone-900">Tarjeta de Crédito</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Visa, Mastercard, American Express. Tokenización segura.</span>
                        </label>

                        <!-- Strategy 2: PSE -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="pse" class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('pse')">
                                <span class="font-bold text-sm text-stone-900">PSE Débito Bancario</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Bancolombia, Nequi, Davivienda, Banco de Bogotá.</span>
                        </label>

                        <!-- Strategy 3: PayPal -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="paypal" class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('paypal')">
                                <span class="font-bold text-sm text-stone-900">PayPal Internacional</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Para compras internacionales desde EE.UU., Europa y el mundo.</span>
                        </label>

                        <!-- Strategy 4 + Adapter: Legacy Bank -->
                        <label class="p-4 rounded-2xl border border-amber-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-amber-50/40 has-[:checked]:bg-amber-50 has-[:checked]:border-amber-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="legacy_bank" class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('legacy')">
                                <span class="font-bold text-sm text-stone-900">Red Bancaria Tradicional</span>
                            </div>
                            <span class="text-[11px] text-amber-800 font-medium mt-2">⚡ Integrada vía Patrón Adapter sobre SDK externo arcaico.</span>
                        </label>
                    </div>

                    <!-- Campos dinámicos simulados de pago -->
                    <div id="paymentInputsContainer" class="p-4 rounded-2xl bg-stone-100 text-xs space-y-3">
                        <div id="ccFields">
                            <label class="block font-semibold text-stone-700 mb-1">Número de Tarjeta Simulado</label>
                            <input type="text" name="card_number" value="4532 1100 2200 3300" class="w-full px-3 py-2 rounded-lg border border-stone-300 font-mono text-xs">
                        </div>
                        <div id="pseFields" class="hidden">
                            <label class="block font-semibold text-stone-700 mb-1">Seleccione Entidad Bancaria (PSE)</label>
                            <select name="pse_bank" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs">
                                <option value="Bancolombia">Bancolombia</option>
                                <option value="Davivienda">Davivienda / Daviplata</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Banco de Bogota">Banco de Bogotá</option>
                            </select>
                        </div>
                        <div id="paypalFields" class="hidden">
                            <label class="block font-semibold text-stone-700 mb-1">Correo Cuenta PayPal</label>
                            <input type="email" name="paypal_email" value="cliente@paypal.com" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs">
                        </div>
                        <div id="legacyFields" class="hidden text-stone-600">
                            <p class="font-bold text-amber-900">Demostración Patrón Adapter:</p>
                            <p>Se invocará el método arcaico <code class="font-mono text-stone-800">execute_transaction_v2()</code> de la clase incompatible <code class="font-mono text-stone-800">ExternalLegacyBankGateway</code> mediante la envoltura <code class="font-mono text-stone-800">LegacyBankAdapter</code>.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. PATRÓN FACTORY METHOD: Tipo de Comprobante -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">4</span>
                            <span>Comprobante Contable (Patrón Factory Method)</span>
                        </h2>
                        <span class="text-[11px] font-bold text-blue-800 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200">
                            Creator Concreto sin switch
                        </span>
                    </div>
                    <p class="text-xs text-stone-500">
                        La creación de la factura se delega a <code class="bg-stone-100 px-1 py-0.5 rounded text-stone-700 font-mono">ReceiptFactory::createReceipt()</code> implementado por <code class="font-mono">ElectronicInvoiceFactory</code> o <code class="font-mono">SimpleTicketFactory</code>.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="invoice_type" value="electronic" checked class="text-clay-600 focus:ring-clay-500">
                                <span class="font-bold text-sm text-stone-900">Factura Electrónica DIAN</span>
                            </div>
                            <p class="text-[11px] text-stone-500 mt-2">Genera CUFE (Código Único), firma digital, QR de validación fiscal y desglose formal de IVA. <em>(ElectronicInvoiceFactory)</em></p>
                        </label>

                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="invoice_type" value="ticket" class="text-clay-600 focus:ring-clay-500">
                                <span class="font-bold text-sm text-stone-900">Tirilla POS Feria Artesanal</span>
                            </div>
                            <p class="text-[11px] text-stone-500 mt-2">Ticket simplificado térmico de venta directa en taller campesino con agradecimiento al artesano. <em>(SimpleTicketFactory)</em></p>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Columna Derecha: Resumen de Costos y Confirmación (1 Col) -->
            <div class="space-y-6">

                <div class="bg-stone-900 text-stone-100 p-6 sm:p-8 rounded-3xl shadow-xl sticky top-28 space-y-6">
                    <h3 class="font-serif font-bold text-xl text-amber-200 border-b border-stone-800 pb-3">
                        Resumen de la Orden
                    </h3>

                    <!-- Mini Lista de Ítems -->
                    <div class="space-y-3 text-xs max-h-48 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center text-stone-300">
                                <span class="truncate max-w-[170px]">{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                                <span class="font-mono text-stone-200 font-bold">${{ number_format((float) ($item['price'] * $item['quantity']), 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desglose de Precios Dinámico -->
                    <div class="border-t border-stone-800 pt-4 space-y-2 text-xs">
                        <div class="flex justify-between text-stone-400">
                            <span>Subtotal Artesanías:</span>
                            <span class="font-mono text-stone-200 font-bold" id="lblSubtotal">${{ number_format($subtotal, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-amber-300" id="rowGiftWrap" style="display: none;">
                            <span>+ Empaque en Mimbre (Decorator):</span>
                            <span class="font-mono font-bold">+${{ number_format($giftWrapCost, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-amber-300" id="rowInsurance" style="{{ $hasFragile ? '' : 'display: none;' }}">
                            <span>+ Seguro Roturas (Decorator):</span>
                            <span class="font-mono font-bold">+${{ number_format($insuranceCost, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-emerald-400">
                            <span>Despacho Nacional:</span>
                            <span class="font-bold">¡Cortesía Cultural!</span>
                        </div>

                        <div class="border-t border-stone-700 pt-3 flex justify-between items-center text-base">
                            <span class="font-bold text-white">TOTAL FINAL:</span>
                            <span class="font-serif font-bold text-2xl text-amber-400 font-mono" id="lblGrandTotal">
                                ${{ number_format($subtotal + ($hasFragile ? $insuranceCost : 0), 0, ',', '.') }} COP
                            </span>
                        </div>
                    </div>

                    <!-- Nota sobre Patrón Observer -->
                    <div class="p-3.5 rounded-xl bg-stone-800/80 border border-stone-700 text-[11px] text-stone-300 space-y-1">
                        <p class="font-bold text-emerald-400 flex items-center gap-1">
                            <span>📡</span> Patrón Observer listo para dispararse:
                        </p>
                        <p class="text-stone-400 leading-tight">
                            Al confirmar, <code class="text-white">OrderSubject</code> notificará atómicamente a: <span class="text-amber-300">AuditLogObserver</span> (BD), <span class="text-amber-300">ArtisanNotificationObserver</span> (Alerta Taller) y <span class="text-amber-300">StockReductionObserver</span>.
                        </p>
                    </div>

                    <!-- Botón de Confirmación de Compra -->
                    <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-clay-500 to-clay-600 hover:from-clay-600 hover:to-clay-700 text-white font-bold text-sm shadow-lg hover:shadow-xl transition transform active:scale-95 flex items-center justify-center gap-2">
                        <span>Pagar y Completar Pedido</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    const baseSubtotal = {{ $subtotal }};
    const giftWrapFee = {{ $giftWrapCost }};
    const insuranceFee = {{ $insuranceCost }};

    function updateTotal() {
        const wantsWrap = document.getElementById('giftWrapCheck').checked;
        const wantsInsurance = document.getElementById('insuranceCheck').checked;

        let total = baseSubtotal;

        const rowWrap = document.getElementById('rowGiftWrap');
        const rowIns = document.getElementById('rowInsurance');

        if (wantsWrap) {
            total += giftWrapFee;
            rowWrap.style.display = 'flex';
        } else {
            rowWrap.style.display = 'none';
        }

        if (wantsInsurance) {
            total += insuranceFee;
            rowIns.style.display = 'flex';
        } else {
            rowIns.style.display = 'none';
        }

        document.getElementById('lblGrandTotal').innerText = '$' + new Intl.NumberFormat('es-CO').format(total) + ' COP';
    }

    function togglePaymentFields(type) {
        document.getElementById('ccFields').classList.add('hidden');
        document.getElementById('pseFields').classList.add('hidden');
        document.getElementById('paypalFields').classList.add('hidden');
        document.getElementById('legacyFields').classList.add('hidden');

        if (type === 'cc') document.getElementById('ccFields').classList.remove('hidden');
        if (type === 'pse') document.getElementById('pseFields').classList.remove('hidden');
        if (type === 'paypal') document.getElementById('paypalFields').classList.remove('hidden');
        if (type === 'legacy') document.getElementById('legacyFields').classList.remove('hidden');
    }

    // Inicializar estado de interfaz
    document.addEventListener('DOMContentLoaded', () => {
        updateTotal();
    });
</script>
@endsection
