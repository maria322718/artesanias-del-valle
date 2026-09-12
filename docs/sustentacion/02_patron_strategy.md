# Dimensión 2 y 3: Patrón de Diseño Strategy (Comportamiento)

## 1. Problema Diagnosticado en el Código "Antes"
En el controlador original (`legacy/LegacyCheckoutController.php:97-133`), la selección y procesamiento del medio de pago se resolvía mediante una cascada condicional de `if / elseif / else`:

```php
// ANTES (LegacyCheckoutController.php:97-133)
if ($paymentMethod === 'credit_card') {
    // 15 líneas de lógica de tarjeta
} elseif ($paymentMethod === 'pse') {
    // 12 líneas de lógica bancaria PSE
} elseif ($paymentMethod === 'paypal') {
    // 10 líneas de lógica PayPal
} elseif ($paymentMethod === 'legacy_bank') {
    // Invocación arcaica
} else {
    throw new Exception("Método no soportado");
}
```

### Problemas Detectados:
1. **Violación flagrante de OCP (Open/Closed Principle)**: Cada nuevo método de pago (ej. Apple Pay, Nequi, Daviplata o Cripto) obliga a editar el controlador existente, retestear flujos previos y arriesgar regresiones en producción.
2. **Alta Complejidad Ciclomática**: El método acumula bifurcaciones lógicas y variables temporales que dificultan el seguimiento y comprensión.
3. **Imposibilidad de Pruebas Unitarias Aisladas**: No es posible probar el cobro con PSE de forma independiente sin instanciar y configurar todo el controlador y sus dependencias HTTP.

---

## 2. Solución Aplicada con el Patrón Strategy (GoF)

Se desacopla la familia de algoritmos de pago bajo la interfaz unificada `PaymentStrategyInterface`. Cada medio de pago es una clase autónoma que encapsula su propia lógica de validación, comunicación y respuesta. La selección de la estrategia se realiza mediante inyección de dependencias a través de un registro dinámico (`PaymentStrategyRegistry`), **sin utilizar condicionales switch/case**.

```
               ┌──────────────────────────────┐
               │  PaymentStrategyInterface    │
               ├──────────────────────────────┤
               │ + pay(amount, data): Result  │
               └──────────────┬───────────────┘
                              │
       ┌──────────────────────┼──────────────────────┐
       │                      │                      │
┌──────┴──────────────┐ ┌─────┴─────────────┐ ┌──────┴──────────────┐
│CreditCardPayment... │ │PSEPaymentStrategy │ │PaypalPaymentStrategy│
├─────────────────────┤ ├───────────────────┤ ├─────────────────────┤
│+ pay(amount, data)  │ │+ pay(amount, data)│ │+ pay(amount, data)  │
└─────────────────────┘ └───────────────────┘ └─────────────────────┘
```

### Código "Después" (Refactorizado):

```php
// app/Patterns/Strategy/PaymentStrategyInterface.php
declare(strict_types=1);

namespace App\Patterns\Strategy;

interface PaymentStrategyInterface
{
    public function pay(float $amount, array $paymentDetails): PaymentResult;
    public function getMethodCode(): string;
    public function getMethodName(): string;
}

// app/Services/CheckoutService.php (Uso limpio)
$strategy = $this->strategyRegistry->get($paymentMethodCode);
$paymentResult = $strategy->pay($grandTotal, $paymentPayload);

if (!$paymentResult->isSuccessful()) {
    throw new PaymentFailedException($paymentResult->getErrorMessage());
}
```

---

## 3. Justificación Arquitectónica: ¿Por qué Strategy y por qué NO un if/else?

| Criterio Técnico | Enfoque Legacy (Cascada `if/else`) | Enfoque Patrón Strategy (GoF) |
| :--- | :--- | :--- |
| **Principio Open/Closed (OCP)** | ❌ **Violado**: Modifica código preexistente para agregar medios de pago. | ✅ **Cumplido**: Se añade una nueva clase `NequiPaymentStrategy` y se registra en el contenedor sin tocar ni una línea del código existente. |
| **Principio Single Responsibility (SRP)** | ❌ Mezcla parsing HTTP, cálculo de totales y llamadas API de terceros en un único archivo. | ✅ Cada clase de estrategia se encarga exclusivamente de interactuar con su proveedor de pago. |
| **Testabilidad (Unit Testing)** | ❌ Requiere mocks complejos del controlador HTTP y requests simulados. | ✅ Cada estrategia se prueba de manera 100% aislada con tests unitarios rápidos y deterministas. |
| **Sustitución de Liskov (LSP)** | ❌ Nulo. Respuestas con arrays heterogéneos y tipos variables. | ✅ Garantizado: Todas las estrategias implementan `PaymentStrategyInterface` y devuelven un objeto inmutable `PaymentResult`. |
| **Intercambiabilidad en Caliente** | ❌ Imposible sin recompilar o agregar lógica de enrutamiento estático. | ✅ Las estrategias pueden cambiarse dinámicamente según el país del cliente, monto de la transacción o disponibilidad de pasarelas. |
