@extends('layouts.app')

@section('title', 'Finalizar Compra — Artesanías del Valle')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <div class="border-b border-stone-200 pb-4">
        <span class="px-3 py-1 bg-clay-100 text-clay-800 text-xs font-bold rounded-full uppercase tracking-wider">
            Compra Segura &middot; Envío Directo desde Talleres
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 mt-2">Finalizar Compra de Artesanías</h1>
        <p class="text-sm text-stone-500 mt-1">Completa los datos de envío, personaliza tu empaque y selecciona tu método de pago preferido.</p>
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

                <!-- 2. Servicios de Valor Agregado -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">2</span>
                            <span>Servicios Adicionales para tu Envío</span>
                        </h2>
                        <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2.5 py-1 rounded-md border border-stone-200">
                            Opciones de Entrega
                        </span>
                    </div>
                    <p class="text-xs text-stone-500">
                        Personaliza tu pedido con empaques tradicionales y protección especializada para tus piezas artesanales.
                    </p>

                    <div class="space-y-3 pt-2">
                        <!-- Servicio 1: Empaque de mimbre -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border border-stone-200 hover:border-clay-300 cursor-pointer transition bg-stone-50/50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-500">
                            <input type="checkbox" name="gift_wrap" value="1" id="giftWrapCheck" class="mt-1 w-4 h-4 text-clay-600 rounded border-stone-300 focus:ring-clay-500" onchange="updateTotal()">
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-stone-900 text-sm">🧺 Empaque de Regalo Ecológico en Mimbre</span>
                                    <span class="font-mono font-bold text-clay-700 text-sm">+${{ number_format($giftWrapCost, 0, ',', '.') }} COP</span>
                                </div>
                                <p class="text-stone-500 mt-0.5">Canasto artesanal tejido a mano en fibra natural de plátano con lazo de fique biodegradable ideal para obsequios especiales.</p>
                            </div>
                        </label>

                        <!-- Servicio 2: Seguro de rotura -->
                        <label class="flex items-start gap-3 p-4 rounded-2xl border border-stone-200 hover:border-clay-300 cursor-pointer transition bg-stone-50/50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-500">
                            <input type="checkbox" name="artisan_insurance" value="1" id="insuranceCheck" {{ $hasFragile ? 'checked' : '' }} class="mt-1 w-4 h-4 text-clay-600 rounded border-stone-300 focus:ring-clay-500" onchange="updateTotal()">
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-stone-900 text-sm">🛡️ Seguro contra Rotura de Piezas Frágiles</span>
                                    <span class="font-mono font-bold text-clay-700 text-sm">+${{ number_format($insuranceCost, 0, ',', '.') }} COP (5%)</span>
                                </div>
                                <p class="text-stone-500 mt-0.5">Reposición 100% garantizada ante accidentes o roturas durante el transporte para cerámicas, barro y piezas delicadas.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. Métodos de Pago Seguros -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">3</span>
                            <span>Selecciona tu Método de Pago</span>
                        </h2>
                        <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                            Pago Seguro Cifrado
                        </span>
                    </div>
                    <p class="text-xs text-stone-500">
                        Aceptamos los principales medios de pago en Colombia y pasarelas internacionales con acreditación instantánea.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <!-- Método 1: Tarjeta -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="credit_card" checked class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('cc')">
                                <span class="font-bold text-sm text-stone-900">Tarjeta de Crédito / Débito</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Visa, Mastercard, American Express. Pagos protegidos.</span>
                        </label>

                        <!-- Método 2: PSE -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="pse" class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('pse')">
                                <span class="font-bold text-sm text-stone-900">PSE Débito Bancario</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Bancolombia, Nequi, Davivienda, Daviplata y más.</span>
                        </label>

                        <!-- Método 3: PayPal -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="paypal" class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('paypal')">
                                <span class="font-bold text-sm text-stone-900">PayPal Internacional</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Para compras internacionales desde cualquier país del mundo.</span>
                        </label>

                        <!-- Método 4: Transferencia Bancaria -->
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition flex flex-col justify-between bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="legacy_bank" class="text-clay-600 focus:ring-clay-500" onclick="togglePaymentFields('legacy')">
                                <span class="font-bold text-sm text-stone-900">Transferencia / Corresponsal</span>
                            </div>
                            <span class="text-[11px] text-stone-500 mt-2">Pago a través de red bancaria tradicional y corresponsales.</span>
                        </label>
                    </div>

                    <!-- Campos dinámicos de pago -->
                    <div id="paymentInputsContainer" class="p-4 rounded-2xl bg-stone-100 text-xs space-y-3">
                        <div id="ccFields">
                            <label class="block font-semibold text-stone-700 mb-1">Número de Tarjeta</label>
                            <input type="text" name="card_number" value="4532 1100 2200 3300" class="w-full px-3 py-2 rounded-lg border border-stone-300 font-mono text-xs">
                        </div>
                        <div id="pseFields" class="hidden">
                            <label class="block font-semibold text-stone-700 mb-1">Selecciona tu Entidad Bancaria (PSE)</label>
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
                            <p class="font-bold text-stone-800">Instrucciones de Pago Bancario:</p>
                            <p class="mt-1">Al completar el pedido recibirás un código de referencia para pagar en cualquier corresponsal bancario o transferencia directa.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Tipo de Comprobante -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-xl font-bold text-stone-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-clay-600 text-white text-xs flex items-center justify-center font-sans font-bold">4</span>
                            <span>Tipo de Comprobante de Compra</span>
                        </h2>
                        <span class="text-[11px] font-bold text-stone-600 bg-stone-100 px-2.5 py-1 rounded-md border border-stone-200">
                            Documento Oficial
                        </span>
                    </div>
                    <p class="text-xs text-stone-500">
                        Elige el tipo de documento comercial o tributario que deseas recibir con tu orden.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="invoice_type" value="electronic" checked class="text-clay-600 focus:ring-clay-500">
                                <span class="font-bold text-sm text-stone-900">Factura Electrónica</span>
                            </div>
                            <p class="text-[11px] text-stone-500 mt-2">Comprobante fiscal con código CUFE, validación tributaria y desglose detallado de impuestos.</p>
                        </label>

                        <label class="p-4 rounded-2xl border border-stone-200 cursor-pointer hover:border-clay-400 transition bg-stone-50 has-[:checked]:bg-clay-50 has-[:checked]:border-clay-600">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="invoice_type" value="ticket" class="text-clay-600 focus:ring-clay-500">
                                <span class="font-bold text-sm text-stone-900">Tirilla de Venta Directa</span>
                            </div>
                            <p class="text-[11px] text-stone-500 mt-2">Comprobante simplificado de venta de taller artesanal con agradecimiento y certificado de autenticidad.</p>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Columna Derecha: Resumen de Costos y Confirmación (1 Col) -->
            <div class="space-y-6">

                <div class="bg-stone-900 text-stone-100 p-6 sm:p-8 rounded-3xl shadow-xl sticky top-28 space-y-6">
                    <h3 class="font-serif font-bold text-xl text-amber-200 border-b border-stone-800 pb-3">
                        Resumen del Pedido
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
                            <span>+ Empaque Especial en Mimbre:</span>
                            <span class="font-mono font-bold">+${{ number_format($giftWrapCost, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-amber-300" id="rowInsurance" style="{{ $hasFragile ? '' : 'display: none;' }}">
                            <span>+ Seguro contra Rotura:</span>
                            <span class="font-mono font-bold">+${{ number_format($insuranceCost, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-emerald-400">
                            <span>Despacho Nacional:</span>
                            <span class="font-bold">¡Envío Gratuito!</span>
                        </div>

                        <div class="border-t border-stone-700 pt-3 flex justify-between items-center text-base">
                            <span class="font-bold text-white">TOTAL FINAL:</span>
                            <span class="font-serif font-bold text-2xl text-amber-400 font-mono" id="lblGrandTotal">
                                ${{ number_format($subtotal + ($hasFragile ? $insuranceCost : 0), 0, ',', '.') }} COP
                            </span>
                        </div>
                    </div>

                    <!-- Garantías de Compra Segura -->
                    <div class="p-3.5 rounded-xl bg-stone-800/80 border border-stone-700 text-[11px] text-stone-300 space-y-1">
                        <p class="font-bold text-amber-300 flex items-center gap-1">
                            <span>🛡️</span> Compra 100% Protegida y Garantizada
                        </p>
                        <p class="text-stone-400 leading-tight">
                            Tu compra respalda de manera directa a familias artesanas de Colombia. Embalaje protegido y despacho con seguimiento en línea.
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
