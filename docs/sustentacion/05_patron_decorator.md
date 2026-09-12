# Dimensión 2 y 3: Patrón de Diseño Decorator (Estructural)

## 1. Problema Diagnosticado en el Código "Antes"
En la versión original (`legacy/LegacyCheckoutController.php:83-93`), los costos adicionales correspondientes a servicios opcionales de las artesanías se calculaban mediante banderas primitivas booleanas y acumuladores manuales:

```php
// ANTES (LegacyCheckoutController.php:83-93)
$additionalCosts = 0.0;
if ($wantsGiftWrap) {
    $additionalCosts += 15000.0; // Empaque en mimbre
}
if ($wantsInsurance) {
    $additionalCosts += ($subtotal * 0.05); // Seguro del 5% para piezas frágiles
}
$grandTotal = $subtotal + $shippingCost + $additionalCosts;
```

### Problemas Detectados:
1. **Falta de Abstracción y Rigidez**: No existe un concepto polimórfico de "Servicio Adicional" o "Detalle de Costo". Todo se reduce a sumas directas de floats sin metadata, trazabilidad contable ni descripción al comprador.
2. **Explosión Combinatoria si se usara Herencia**:
   Si intentáramos modelar esto mediante herencia orientada a objetos convencional, tendríamos:
   - `Order`
   - `OrderWithGiftWrap`
   - `OrderWithInsurance`
   - `OrderWithGiftWrapAndInsurance`
   
   Con $N$ servicios opcionales (empaque ecológico, seguro de rotura, certificado de origen indígena, tarjeta personalizada caligrafiada), el número de subclases requeridas crece exponencialmente a razón de:
   $$\text{Subclases requeridas} = 2^N$$
   Para tan solo 4 servicios adicionales, ¡necesitaríamos **16 subclases estáticas**!
3. **Violación de OCP**: Agregar un nuevo servicio implica añadir más campos a la base de datos, más parámetros booleanos en el request y más bloques `if` en el cálculo central.

---

## 2. Solución Aplicada con el Patrón Decorator (GoF)

Se implementa el patrón **Decorator** aplicando el principio de diseño fundamental: **"Favorecer la composición de objetos sobre la herencia de clases"**.

- **Componente Base Abstracto**: `OrderCostInterface` con métodos:
  - `calculateTotal(): float`
  - `getBreakdown(): array` (retorna descripción y costo unitario de cada capa).
- **Componente Concreto**: `BaseOrderCost` que calcula únicamente el valor de los productos artesanales.
- **Decorador Abstracto**: `OrderCostDecorator` que implementa `OrderCostInterface` y mantiene una referencia interna `$decorated` a la que delega las llamadas.
- **Decoradores Concretos**:
  - `GiftWrapDecorator`: Agrega el servicio de empaque artesanal ecológico tejido en fibra de mimbre o plátano (+ $15,000 COP).
  - `ArtisanInsuranceDecorator`: Agrega el seguro de transporte contra rotura para piezas frágiles de cerámica de Ráquira o barniz de Pasto (+ 5% del valor base).

```
                  ┌──────────────────────┐
                  │  OrderCostInterface  │
                  ├──────────────────────┤
                  │+ calculateTotal(): f │
                  │+ getBreakdown(): arr │
                  └──────────▲───────────┘
                             │
            ┌────────────────┴────────────────┐
            │                                 │
   ┌────────┴─────────┐             ┌─────────┴─────────┐
   │  BaseOrderCost   │             │OrderCostDecorator │ (Abstract Decorator)
   ├──────────────────┤             ├───────────────────┤
   │- productsTotal: f│             │# decorated: Inter.│
   │+ calculateTotal()│             │+ calculateTotal() │
   │+ getBreakdown()  │             │+ getBreakdown()   │
   └──────────────────┘             └─────────▲─────────┘
                                              │
                         ┌────────────────────┴────────────────────┐
                         │                                         │
             ┌───────────┴──────────┐                  ┌───────────┴───────────────┐
             │   GiftWrapDecorator  │                  │ ArtisanInsuranceDecorator │
             ├──────────────────────┤                  ├───────────────────────────┤
             │+ calculateTotal()    │                  │+ calculateTotal()         │
             │+ getBreakdown()      │                  │+ getBreakdown()           │
             └──────────────────────┘                  └───────────────────────────┘
```

### Composición Dinámica en Tiempo de Ejecución (Runtime Stacking):

```php
// Se crea el costo base de las artesanías
$costCalculator = new BaseOrderCost($subtotal);

// Si el usuario solicitó empaque de regalo ecológico, se envuelve dinámicamente:
if ($request->boolean('gift_wrap')) {
    $costCalculator = new GiftWrapDecorator($costCalculator);
}

// Si solicitó seguro para artesanías frágiles, se envuelve nuevamente:
if ($request->boolean('artisan_insurance')) {
    $costCalculator = new ArtisanInsuranceDecorator($costCalculator);
}

// El cálculo final es polimórfico y autodescriptivo
$finalTotal = $costCalculator->calculateTotal();
$itemizedBreakdown = $costCalculator->getBreakdown();
```

---

## 3. Justificación Arquitectónica: ¿Por qué Decorator frente a Herencia o Flags?

| Criterio Técnico | Herencia Clásica | Banderas Booleanas (`if/else`) | Patrón Decorator (GoF) |
| :--- | :--- | :--- | :--- |
| **Escalabilidad ante $N$ Opciones** | ❌ Pésima: $2^N$ clases estáticas en el proyecto. | ❌ Pésima: Cascada inmanejable de variables booleanas. | ✅ **Óptima**: Solo $N$ clases de decoradores creadas una única vez. |
| **Composición en Caliente (Runtime)** | ❌ Rígida: Determinada en tiempo de compilación. | ⚠️ Mutable mediante mutación de estado procedural. | ✅ **Elegante y Flexible**: Se apilan envoltorios según la petición del cliente. |
| **Principio Open/Closed (OCP)** | ❌ Modifica la jerarquía de herencia. | ❌ Modifica el método de cálculo de la clase. | ✅ Nuevos servicios (ej. `CertificateOfOriginDecorator`) no tocan clases existentes. |
| **Transparencia para el Cliente** | ⚠️ El cliente debe conocer la subclase exacta. | ❌ El cliente debe recordar calcular y sumar manualmente. | ✅ El cliente solo ve `OrderCostInterface`; no necesita saber cuántas capas envuelven al objeto. |
