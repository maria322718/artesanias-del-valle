@extends('layouts.app')

@section('title', 'Finalizar Compra — Artesanías del Valle')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- Encabezado de Checkout -->
    <div class="border-b border-[#E8E2D9] pb-4">
        <span class="inline-block px-3 py-1 bg-[#FAF5F2] text-[#8D341B] text-[11px] font-bold rounded-full uppercase tracking-wider border border-[#EBD4C9]">
            Compra Protegida &bull; Envío Directo desde Talleres de Origen
        </span>
        <h1 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold text-[#231F1D] mt-2">
            Finalizar Compra de Artesanías
        </h1>
        <p class="text-xs text-[#6E6864] mt-1">
            Completa los datos de envío, personaliza los servicios adicionales y selecciona tu método de pago preferido.
        </p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Columna Izquierda: Pasos de Facturación y Opciones (2 Cols) -->
            <div class="lg:col-span-2 space-y-7">

                <!-- 1. Datos del Cliente -->
                <div class="bg-gradient-to-b from-white to-[#FDFBF8] p-6 sm:p-7 rounded-[16px] border border-[#E5DDD2] shadow-sm space-y-4">
                    <h2 class="font-serif text-lg font-bold text-[#231F1D] flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-full bg-gradient-to-r from-[#8D341B] to-[#A34328] text-white text-xs flex items-center justify-center font-sans font-bold shadow-sm shadow-[#8D341B]/30">
                            1
                        </span>
                        <span>Información del Comprador & Entrega</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Nombre Completo *</label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', 'Camila Benavides') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DECFC1] focus:outline-none focus:border-[#8D341B] text-xs bg-white">
                        </div>
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Correo Electrónico *</label>
                            <input type="email" name="customer_email" required value="{{ old('customer_email', 'camilabenavides@gmail.com') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DECFC1] focus:outline-none focus:border-[#8D341B] text-xs bg-white">
                        </div>
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Teléfono Móvil / WhatsApp *</label>
                            <input type="text" name="customer_phone" required value="{{ old('customer_phone', '3158889922') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DECFC1] focus:outline-none focus:border-[#8D341B] text-xs bg-white">
                        </div>
                        <div>
                            <label class="block font-semibold text-stone-700 mb-1">Dirección de Entrega en Colombia *</label>
                            <input type="text" name="shipping_address" required value="{{ old('shipping_address', 'Carrera 7 # 71-21, Apto 502') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-[#DECFC1] focus:outline-none focus:border-[#8D341B] text-xs bg-white">
                        </div>
                    </div>
                </div>

                <!-- 2. Servicios de Valor Agregado (Sin Emojis, con Iconos SVG) -->
                <div class="bg-gradient-to-b from-white to-[#FDFBF8] p-6 sm:p-7 rounded-[16px] border border-[#E5DDD2] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-lg font-bold text-[#231F1D] flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-gradient-to-r from-[#8D341B] to-[#A34328] text-white text-xs flex items-center justify-center font-sans font-bold shadow-sm shadow-[#8D341B]/30">
                                2
                            </span>
                            <span>Servicios Adicionales para tu Envío</span>
                        </h2>
                        <span class="text-[10.5px] font-bold text-[#8D341B] bg-[#FAF0EB] px-3 py-1 rounded-full border border-[#F2D1C2]">
                            Opciones Especiales
                        </span>
                    </div>
                    <p class="text-xs text-[#6E6864]">
                        Personaliza tu pedido con empaques tradicionales y protección especializada para tus piezas artesanales.
                    </p>

                    <div class="space-y-3 pt-1">
                        <!-- Servicio 1: Empaque de mimbre -->
                        <label class="flex items-start gap-3 p-4 rounded-xl border border-[#E5DDD2] hover:border-[#8D341B] cursor-pointer transition bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B] has-[:checked]:shadow-xs">
                            <input type="checkbox" name="gift_wrap" value="1" id="giftWrapCheck" class="mt-1 w-4 h-4 text-[#8D341B] rounded border-[#DECFC1] focus:ring-[#8D341B]" onchange="updateTotal()">
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-stone-900 text-xs sm:text-sm flex items-center gap-1.5">
                                        <!-- Icono SVG Canasto Artesanal -->
                                        <svg class="w-4 h-4 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span>Empaque de Regalo Ecológico en Mimbre</span>
                                    </span>
                                    <span class="font-mono font-bold text-[#8D341B] text-xs sm:text-sm">+${{ number_format($giftWrapCost, 0, ',', '.') }} COP</span>
                                </div>
                                <p class="text-[#6E6864] mt-0.5 leading-relaxed">
                                    Canasto artesanal tejido a mano en fibra natural de plátano con lazo de fique biodegradable ideal para obsequios especiales.
                                </p>
                            </div>
                        </label>

                        <!-- Servicio 2: Seguro de rotura -->
                        <label class="flex items-start gap-3 p-4 rounded-xl border border-[#E5DDD2] hover:border-[#8D341B] cursor-pointer transition bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B] has-[:checked]:shadow-xs">
                            <input type="checkbox" name="artisan_insurance" value="1" id="insuranceCheck" {{ $hasFragile ? 'checked' : '' }} class="mt-1 w-4 h-4 text-[#8D341B] rounded border-[#DECFC1] focus:ring-[#8D341B]" onchange="updateTotal()">
                            <div class="flex-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-stone-900 text-xs sm:text-sm flex items-center gap-1.5">
                                        <!-- Icono SVG Escudo Protector -->
                                        <svg class="w-4 h-4 text-[#8D341B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>Seguro contra Rotura de Piezas Frágiles</span>
                                    </span>
                                    <span class="font-mono font-bold text-[#8D341B] text-xs sm:text-sm">+${{ number_format($insuranceCost, 0, ',', '.') }} COP (5%)</span>
                                </div>
                                <p class="text-[#6E6864] mt-0.5 leading-relaxed">
                                    Reposición 100% garantizada ante incidentes de transporte para cerámicas, alfarería y piezas delicadas.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. Métodos de Pago Seguros -->
                <div class="bg-gradient-to-b from-white to-[#FDFBF8] p-6 sm:p-7 rounded-[16px] border border-[#E5DDD2] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-lg font-bold text-[#231F1D] flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-gradient-to-r from-[#8D341B] to-[#A34328] text-white text-xs flex items-center justify-center font-sans font-bold shadow-sm shadow-[#8D341B]/30">
                                3
                            </span>
                            <span>Selecciona tu Método de Pago</span>
                        </h2>
                        <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                            Pago Seguro Cifrado
                        </span>
                    </div>
                    <p class="text-xs text-[#6E6864]">
                        Aceptamos los principales medios de pago en Colombia y pasarelas internacionales con acreditación instantánea.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <!-- Método 1: Tarjeta -->
                        <label class="p-3.5 rounded-xl border border-[#E5DDD2] cursor-pointer hover:border-[#8D341B] transition flex flex-col justify-between bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="credit_card" checked class="text-[#8D341B] focus:ring-[#8D341B]" onclick="togglePaymentFields('cc')">
                                <span class="font-bold text-xs sm:text-sm text-stone-900">Tarjeta de Crédito / Débito</span>
                            </div>
                            <span class="text-[10px] text-[#6E6864] mt-2">Visa, Mastercard, American Express.</span>
                        </label>

                        <!-- Método 2: PSE -->
                        <label class="p-3.5 rounded-xl border border-[#E5DDD2] cursor-pointer hover:border-[#8D341B] transition flex flex-col justify-between bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="pse" class="text-[#8D341B] focus:ring-[#8D341B]" onclick="togglePaymentFields('pse')">
                                <span class="font-bold text-xs sm:text-sm text-stone-900">PSE Débito Bancario</span>
                            </div>
                            <span class="text-[10px] text-[#6E6864] mt-2">Bancolombia, Nequi, Davivienda y más.</span>
                        </label>

                        <!-- Método 3: PayPal -->
                        <label class="p-3.5 rounded-xl border border-[#E5DDD2] cursor-pointer hover:border-[#8D341B] transition flex flex-col justify-between bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="paypal" class="text-[#8D341B] focus:ring-[#8D341B]" onclick="togglePaymentFields('paypal')">
                                <span class="font-bold text-xs sm:text-sm text-stone-900">PayPal Internacional</span>
                            </div>
                            <span class="text-[10px] text-[#6E6864] mt-2">Para compras internacionales desde el exterior.</span>
                        </label>

                        <!-- Método 4: Transferencia Bancaria -->
                        <label class="p-3.5 rounded-xl border border-[#E5DDD2] cursor-pointer hover:border-[#8D341B] transition flex flex-col justify-between bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="legacy_bank" class="text-[#8D341B] focus:ring-[#8D341B]" onclick="togglePaymentFields('legacy')">
                                <span class="font-bold text-xs sm:text-sm text-stone-900">Transferencia / Corresponsal</span>
                            </div>
                            <span class="text-[10px] text-[#6E6864] mt-2">Pago en red bancaria y corresponsales.</span>
                        </label>
                    </div>

                    <!-- Campos dinámicos de pago -->
                    <div id="paymentInputsContainer" class="p-4 rounded-xl bg-[#FBF9F5] border border-[#E5DDD2] text-xs space-y-3">
                        <div id="ccFields">
                            <label class="block font-semibold text-stone-700 mb-1">Número de Tarjeta</label>
                            <input type="text" name="card_number" value="4532 1100 2200 3300" class="w-full px-3 py-2 rounded-lg border border-[#DECFC1] font-mono text-xs focus:outline-none focus:border-[#8D341B] bg-white">
                        </div>
                        <div id="pseFields" class="hidden">
                            <label class="block font-semibold text-stone-700 mb-1">Selecciona tu Entidad Bancaria (PSE)</label>
                            <select name="pse_bank" class="w-full px-3 py-2 rounded-lg border border-[#DECFC1] text-xs focus:outline-none focus:border-[#8D341B] bg-white">
                                <option value="Bancolombia">Bancolombia</option>
                                <option value="Davivienda">Davivienda / Daviplata</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Banco de Bogota">Banco de Bogotá</option>
                            </select>
                        </div>
                        <div id="paypalFields" class="hidden">
                            <label class="block font-semibold text-stone-700 mb-1">Correo Cuenta PayPal</label>
                            <input type="email" name="paypal_email" value="cliente@paypal.com" class="w-full px-3 py-2 rounded-lg border border-[#DECFC1] text-xs focus:outline-none focus:border-[#8D341B] bg-white">
                        </div>
                        <div id="legacyFields" class="hidden text-stone-600">
                            <p class="font-bold text-stone-800">Instrucciones de Pago Bancario:</p>
                            <p class="mt-1">Al completar el pedido recibirás un código de referencia para pagar en cualquier corresponsal bancario o transferencia directa.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Tipo de Comprobante -->
                <div class="bg-gradient-to-b from-white to-[#FDFBF8] p-6 sm:p-7 rounded-[16px] border border-[#E5DDD2] shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-lg font-bold text-[#231F1D] flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-gradient-to-r from-[#8D341B] to-[#A34328] text-white text-xs flex items-center justify-center font-sans font-bold shadow-sm shadow-[#8D341B]/30">
                                4
                            </span>
                            <span>Tipo de Comprobante de Compra</span>
                        </h2>
                        <span class="text-[10.5px] font-bold text-[#8D341B] bg-[#FAF0EB] px-3 py-1 rounded-full border border-[#F2D1C2]">
                            Documento Oficial
                        </span>
                    </div>
                    <p class="text-xs text-[#6E6864]">
                        Elige el tipo de documento comercial o tributario que deseas recibir con tu orden.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <label class="p-3.5 rounded-xl border border-[#E5DDD2] cursor-pointer hover:border-[#8D341B] transition bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="invoice_type" value="electronic" checked class="text-[#8D341B] focus:ring-[#8D341B]">
                                <span class="font-bold text-xs sm:text-sm text-stone-900">Factura Electrónica</span>
                            </div>
                            <span class="text-[10px] text-[#6E6864] mt-2 block">Válida para deducciones tributarias DIAN.</span>
                        </label>

                        <label class="p-3.5 rounded-xl border border-[#E5DDD2] cursor-pointer hover:border-[#8D341B] transition bg-stone-50/50 has-[:checked]:bg-[#FFF5EE] has-[:checked]:border-[#8D341B]">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="invoice_type" value="ticket" class="text-[#8D341B] focus:ring-[#8D341B]">
                                <span class="font-bold text-xs sm:text-sm text-stone-900">Boleta de Compra Simple</span>
                            </div>
                            <span class="text-[10px] text-[#6E6864] mt-2 block">Comprobante de compra personal estándar.</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Columna Derecha: Resumen de Costos y Confirmación (1 Col) -->
            <div class="space-y-6">

                <div class="bg-gradient-to-b from-[#1C1715] to-[#120F0E] text-white p-6 sm:p-7 rounded-[18px] shadow-xl sticky top-24 space-y-5 border border-stone-800">
                    <h3 class="font-serif font-bold text-lg text-white border-b border-stone-800 pb-3 flex items-center justify-between">
                        <span>Resumen del Pedido</span>
                        <span class="text-xs font-sans text-amber-400 font-medium">Colombia</span>
                    </h3>

                    <!-- Mini Lista de Ítems -->
                    <div class="space-y-2.5 text-xs max-h-48 overflow-y-auto pr-1">
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
                            <span>+ Empaque en Mimbre:</span>
                            <span class="font-mono font-bold">+${{ number_format($giftWrapCost, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-amber-300" id="rowInsurance" style="{{ $hasFragile ? '' : 'display: none;' }}">
                            <span>+ Seguro contra Rotura:</span>
                            <span class="font-mono font-bold">+${{ number_format($insuranceCost, 0, ',', '.') }} COP</span>
                        </div>
                        <div class="flex justify-between text-emerald-400">
                            <span>Despacho Nacional:</span>
                            <span class="font-semibold">¡Envío Gratuito!</span>
                        </div>

                        <div class="border-t border-stone-800 pt-3.5 flex justify-between items-center text-sm">
                            <span class="font-bold text-white uppercase text-xs tracking-wider">TOTAL FINAL:</span>
                            <span class="font-serif font-bold text-xl text-[#F59E0B] font-mono" id="lblGrandTotal">
                                ${{ number_format($subtotal + ($hasFragile ? $insuranceCost : 0), 0, ',', '.') }} COP
                            </span>
                        </div>
                    </div>

                    <!-- Garantías de Compra Segura (Sin Emojis) -->
                    <div class="p-3.5 rounded-xl bg-stone-800/60 border border-stone-700/80 text-[11px] text-stone-300 space-y-1">
                        <p class="font-bold text-[#F59E0B] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Compra 100% Protegida</span>
                        </p>
                        <p class="text-stone-400 leading-tight">
                            Tu compra respalda de manera directa a familias artesanas de Colombia. Embalaje protegido y despacho con seguimiento en línea.
                        </p>
                    </div>

                    <!-- Botón de Confirmación de Compra -->
                    <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-[#8D341B] to-[#A34328] hover:from-[#6C230E] hover:to-[#8D341B] text-white font-bold text-xs shadow-lg shadow-[#8D341B]/35 hover:shadow-xl hover:shadow-[#8D341B]/45 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <span>Pagar y Completar Pedido</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
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

    document.addEventListener('DOMContentLoaded', () => {
        updateTotal();
    });
</script>
@endsection
