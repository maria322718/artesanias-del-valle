# Dimensión 2 y 3: Patrón de Diseño Observer (Comportamiento)

## 1. Problema Diagnosticado en el Código "Antes"
En `legacy/LegacyCheckoutController.php:138-157`, una vez aprobado el pago, el controlador ejecutaba de forma síncrona y acoplada tres tareas completamente ajenas al proceso de checkout:

```php
// ANTES (LegacyCheckoutController.php:138-157)
// A. Descuento manual de Stock
foreach ($items as $item) {
    DB::table('products')->where('id', $item['id'])->decrement('stock', $item['quantity']);
}

// B. Envío síncrono de correo al artesano
try {
    Mail::raw("Alerta maestro artesano: Despachar pedido {$transactionId}", function($msg) {});
} catch (Exception $e) {
    Log::error("Fallo enviando alerta al artesano: " . $e->getMessage());
}

// C. Registro de auditoría monolítico
Log::channel('single')->info("LEGACY_AUDIT: Pedido {$transactionId} pagado por {$grandTotal} COP");
```

### Problemas Detectados:
1. **Grave Violación de SRP**: El controlador de compras es responsable de procesar la transacción bancaria, pero además asume responsabilidades de gestión de inventario, notificaciones logísticas externas y auditoría de seguridad.
2. **Punto Único de Falla (Cascada de Caídas)**: Si el servidor SMTP del correo se encuentra temporalmente indisponible o lento, la solicitud HTTP del cliente se bloquea por timeout, haciendo que el usuario piense que su compra falló a pesar de haber sido cobrada.
3. **Alto Acoplamiento y Baja Extensibilidad**: Si el negocio desea sumar analíticas de Google Ads, sincronización con el ERP de artesanos de Boyacá o fidelización por puntos, hay que seguir inflando el método del controlador.

---

## 2. Solución Aplicada con el Patrón Observer (GoF)

Se implementa el patrón **Observer** clásico mediante interfaces explícitas:
- **`OrderSubjectInterface` / `OrderSubject`**: Mantiene la lista de observadores (`attach`, `detach`) y notifica el evento `notify(Order $order, string $event)`.
- **`OrderObserverInterface`**: Contrato obligatorio con el método `update(Order $order, string $event): void`.
- **Observadores Concretos Desacoplados**:
  1. `AuditLogObserver`: Registra una traza inmutable en la tabla PostgreSQL/SQLite `audit_logs` con IP, datos transaccionales y hash de seguridad.
  2. `ArtisanNotificationObserver`: Genera la alerta de despacho dirigida al artesano específico según la comunidad de origen (ej. Asociación de Tejedoras Wayuu o Taller de Ráquira).
  3. `StockReductionObserver`: Realiza el descuento atómico de inventario y previene inconsistencias por sobreventa de piezas únicas hechas a mano.

```
                  ┌───────────────────────┐
                  │ OrderSubjectInterface │
                  ├───────────────────────┤
                  │ + attach(observer)    │
                  │ + detach(observer)    │
                  │ + notify(order, event)│
                  └───────────▲───────────┘
                              │
                    ┌─────────┴─────────┐
                    │   OrderSubject    │
                    └─────────┬─────────┘
                              │ notifica a
                              ▼
                  ┌───────────────────────┐
                  │OrderObserverInterface │
                  ├───────────────────────┤
                  │ + update(order, event)│
                  └───────────▲───────────┘
                              │
         ┌────────────────────┼────────────────────┐
         │                    │                    │
┌────────┴───────────┐ ┌──────┴────────────┐ ┌─────┴────────────────┐
│  AuditLogObserver  │ │ArtisanNotification│ │StockReductionObserver│
│                    │ │     Observer      │ │                      │
├────────────────────┤ ├───────────────────┤ ├──────────────────────┤
│+ update(...)       │ │+ update(...)      │ │+ update(...)         │
└────────────────────┘ └───────────────────┘ └──────────────────────┘
```

---

## 3. Justificación Arquitectónica: ¿Por qué Observer y no llamadas directas en el servicio?

| Criterio Técnico | Llamadas Directas en el Servicio | Patrón Observer (GoF) |
| :--- | :--- | :--- |
| **Principio Single Responsibility (SRP)** | ❌ El servicio de checkout conoce a los artesanos, el almacén y el sistema de auditoría. | ✅ El servicio de checkout solo emite un evento `"order.completed"`. Cada observador tiene una única y exclusiva responsabilidad. |
| **Tolerancia a Fallos y Resiliencia** | ❌ La falla en la notificación al artesano aborta o contamina el flujo de pago del cliente. | ✅ Los observadores pueden ejecutarse de manera asíncrona, encolarse en workers o fallar sin interrumpir la confirmación al usuario. |
| **Principio Abierto/Cerrado (OCP)** | ❌ Cada nuevo requerimiento posventa (ej. Facturación a la DIAN, Marketing) modifica el core del checkout. | ✅ Nuevos observadores (`GoogleAnalyticsObserver`, `LoyaltyPointsObserver`) se suscriben sin tocar una sola línea del checkout. |
| **Testabilidad** | ❌ Pruebas lentas que requieren levantar base de datos, servicio de email y logs simultáneamente. | ✅ Cada observador se prueba unitariamente con un objeto `Order` falso, verificando su comportamiento específico. |
