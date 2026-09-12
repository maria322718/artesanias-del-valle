# Dimensión 2 y 3: Patrón de Diseño Adapter (Estructural)

## 1. Problema Diagnosticado en el Código "Antes"
En el código original (`legacy/LegacyCheckoutController.php:36-37` y `128-132`), el sistema se conectaba a una pasarela bancaria externa arcaica instanciando directamente su clase e invocando métodos con firmas incompatibles y estructuras de datos crudas:

```php
// ANTES (LegacyCheckoutController.php:128-132)
$rawPayload = [
    'monto' => $grandTotal, 
    'divisa' => 'COP', 
    'ref' => 'REF-' . time()
];
// Llamada directa al método incompatible del SDK externo
$res = $this->legacyBankSdk->execute_transaction_v2($rawPayload);
$transactionId = 'LEGACY-TX-8831';
$paymentResponseRaw = ['COD_AUTH' => 'COD_AUT_200', 'MENSAJE' => 'APROBADA'];
```

### Problemas Detectados:
1. **Violación Directa de DIP (Dependency Inversion Principle)**: El módulo de alto nivel del dominio depende de la API no estándar de un SDK de terceros. Si el banco cambia el nombre de la función o los nombres de los campos en el array, todo el dominio se rompe.
2. **Contaminación del Dominio (Falta de Anti-Corruption Layer)**: La terminología externa (`COD_AUTH`, `MENSAJE`, `monto`) invade las capas de aplicación, obligando al resto de la aplicación a lidiar con estructuras de datos no estandarizadas.
3. **Incompatibilidad de Interfaces**: La tienda moderna de artesanías espera trabajar con contratos tipados en inglés estándar (`PaymentGatewayInterface::processPayment(float $amount, string $currency, array $metadata)`), pero la librería externa solo ofrece `execute_transaction_v2(array $payload)`.

---

## 2. Solución Aplicada con el Patrón Adapter (GoF)

Se implementa el patrón **Adapter** para actuar como una **Capa Anticorrupción (Anti-Corruption Layer - ACL)** entre el dominio limpio de "Artesanías del Valle" y la librería bancaria externa incompatible.

- **Target Interface (Contrato Esperado por el Dominio)**:
  `PaymentGatewayInterface` con el método fuertemente tipado:
  `processPayment(float $amount, string $currency, array $metadata): GatewayResponse`
- **Adaptee (Clase Incompatible de Terceros)**:
  `ExternalLegacyBankGateway` con el método:
  `execute_transaction_v2(array $payload): array`
- **Adapter (Clase Adaptadora)**:
  `LegacyBankAdapter implements PaymentGatewayInterface`. Envuelve una instancia de `ExternalLegacyBankGateway`, mapea los argumentos del dominio al formato arcaico del banco y transforma la respuesta cruda en un objeto inmutable `GatewayResponse`.

```
    ┌─────────────────────────┐
    │     Cliente (Dominio)   │
    └────────────┬────────────┘
                 │ depende de
                 ▼
    ┌─────────────────────────┐
    │ PaymentGatewayInterface │ (Target)
    ├─────────────────────────┤
    │+ processPayment(...):   │
    │  GatewayResponse        │
    └────────────▲────────────┘
                 │ implementa
                 │
    ┌────────────┴────────────┐            ┌────────────────────────────┐
    │    LegacyBankAdapter    │ ─────────► │  ExternalLegacyBankGateway │ (Adaptee Incompatible)
    ├─────────────────────────┤  envuelve  ├────────────────────────────┤
    │- adaptee: ExtLegacy...  │            │+ execute_transaction_v2()  │
    │+ processPayment(...)    │            └────────────────────────────┘
    └─────────────────────────┘
```

### Implementación del Adaptador:

```php
// app/Patterns/Adapter/LegacyBankAdapter.php
declare(strict_types=1);

namespace App\Patterns\Adapter;

class LegacyBankAdapter implements PaymentGatewayInterface
{
    public function __construct(
        private readonly ExternalLegacyBankGateway $adaptee
    ) {}

    public function processPayment(float $amount, string $currency, array $metadata): GatewayResponse
    {
        // 1. Traducción del dominio limpio al formato arcaico del Adaptee
        $legacyPayload = [
            'monto' => $amount,
            'divisa' => $currency,
            'ref' => $metadata['order_reference'] ?? ('REF-' . uniqid()),
            'fecha' => date('Y-m-d H:i:s'),
        ];

        // 2. Ejecución sobre el método incompatible
        $rawResult = $this->adaptee->execute_transaction_v2($legacyPayload);

        // 3. Normalización hacia el objeto de valor del dominio
        $isApproved = ($rawResult['COD_RESPUESTA'] ?? '') === 'COD_AUT_200';

        return new GatewayResponse(
            success: $isApproved,
            transactionId: (string) ($rawResult['NUM_AUTORIZACION'] ?? ''),
            message: (string) ($rawResult['MENSAJE'] ?? 'Error desconocido'),
            rawPayload: $rawResult
        );
    }
}
```

---

## 3. Justificación Arquitectónica: ¿Por qué Adapter y qué alternativas se descartaron?

| Criterio Técnico | Modificar la Librería Externa | Wrapper Procedural con Funciones Sueltas | Patrón Adapter (GoF) |
| :--- | :--- | :--- | :--- |
| **Viabilidad Técnica** | ❌ Imposible si la librería viene de un paquete Composer o es un SDK cerrado de un banco. | ⚠️ Genera código procedural disperso difícil de mantener. | ✅ **Totalmente desacoplado**: Trabaja sobre la interfaz sin alterar el código fuente del proveedor. |
| **Cumplimiento de DIP** | ❌ El dominio sigue dependiendo de detalles de bajo nivel. | ❌ Funciones globales sin abstracción de interfaz. | ✅ El dominio depende únicamente de `PaymentGatewayInterface`. |
| **Facilidad de Reemplazo** | ❌ Si el banco migra a una API REST moderna v3, hay que buscar y cambiar código por todo el proyecto. | ⚠️ Hay que actualizar las funciones globales. | ✅ Solo se crea un nuevo adaptador o se reemplaza por otra implementación de `PaymentGatewayInterface`. |
| **Mockeabilidad en Tests** | ❌ Requiere sobreescribir métodos con extensiones de PHP o monkey patching. | ⚠️ Difícil de mockear en PHPUnit estándar. | ✅ Se crea un `MockPaymentGateway` o un mock directo de `PaymentGatewayInterface` en una sola línea. |
