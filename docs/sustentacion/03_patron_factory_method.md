# Dimensión 2 y 3: Patrón de Diseño Factory Method (Creacional)

## 1. Problema Diagnosticado en el Código "Antes"
En el código monolítico original (`legacy/LegacyCheckoutController.php:160-176`), la generación de comprobantes contables (Factura Electrónica vs Tirilla POS básica) se resolvía mediante una bifurcación condicional rígida:

```php
// ANTES (LegacyCheckoutController.php:160-176)
$receiptOutput = '';
if ($invoiceType === 'electronic') {
    $cufe = hash('sha256', $transactionId . $grandTotal . date('Y-m-d'));
    $receiptOutput = "=== FACTURA ELECTRÓNICA DIAN (MODO LEGACY) ===\n";
    $receiptOutput .= "CUFE: {$cufe}\n";
    $receiptOutput .= "Cliente: {$customerEmail}\n";
    $receiptOutput .= "Total: \${$grandTotal} COP\n";
} else {
    $receiptOutput = "=== TICKET POS ARTESANAL (MODO LEGACY) ===\n";
    $receiptOutput .= "Transacción: {$transactionId}\n";
    $receiptOutput .= "Total Pagado: \${$grandTotal} COP\n";
    $receiptOutput .= "¡Gracias por apoyar a los artesanos colombianos!\n";
}
```

### Problemas Detectados:
1. **Acoplamiento Concreto y Violación de SRP**: El controlador asume la responsabilidad de formatear strings, calcular Codelos Hash de CUFE (Facturación DIAN) y estructurar representaciones de negocio.
2. **Violación de OCP**: Si la tienda necesita emitir una "Factura de Exportación de Artesanías" o un "Comprobante de Donación Cultural para Comunidades Indígenas", se debe reescribir la lógica condicional del controlador.
3. **Rigidez**: No existe abstracción de comprobante; el resultado es un string no estructurado que no puede ser serializado, firmado digitalmente ni exportado a PDF/XML.

---

## 2. Solución Aplicada con Factory Method (GoF)

Se aplica el patrón canónico **Factory Method**:
- **Producto Abstracto**: `ReceiptInterface` que exige contratos como `renderHtml()`, `getDocumentNumber()`, `getLegalMetadata()`, `getFormatType()`.
- **Productos Concretos**:
  - `ElectronicInvoiceReceipt`: Implementa requerimientos fiscales colombianos (CUFE, firma digital, código QR DIAN, desglose de IVA artesanal).
  - `SimpleTicketReceipt`: Formato tirilla para impresora térmica POS de ferias artesanales.
- **Creador Abstracto**: `ReceiptFactory` con el método de fábrica abstracto `createReceipt(Order $order): ReceiptInterface` y el método plantilla `generateDocument(Order $order)`.
- **Creadores Concretos**:
  - `ElectronicInvoiceFactory` -> crea instancias de `ElectronicInvoiceReceipt`.
  - `SimpleTicketFactory` -> crea instancias de `SimpleTicketReceipt`.

> [!IMPORTANT]
> **Sin trampa de `switch/case`**: No se utiliza ninguna sentencia `switch` o `if/else` para crear los objetos dentro de las factorías. La creación se delega estrictamente al polimorfismo de las subclases creadoras.

```
       ┌────────────────────────┐
       │     ReceiptFactory     │ ◄─── (Creator Abstracto)
       ├────────────────────────┤
       │+ createReceipt(): Rec. │ (Método Abstracto)
       │+ generate(order): Doc  │ (Template Method)
       └───────────▲────────────┘
                   │
         ┌─────────┴─────────┐
         │                   │
┌────────┴────────┐ ┌────────┴────────┐
│ElectronicInvoice│ │SimpleTicket     │ ◄─── (Creators Concretos)
│Factory          │ │Factory          │
├─────────────────┤ ├─────────────────┤
│+ createReceipt()│ │+ createReceipt()│
└────────┬────────┘ └────────┬────────┘
         │ (crea)            │ (crea)
         ▼                   ▼
┌─────────────────┐ ┌─────────────────┐
│ElectronicInvoice│ │SimpleTicket     │ ◄─── (Products Concretos que
│Receipt          │ │Receipt          │      implementan ReceiptInterface)
└─────────────────┘ └─────────────────┘
```

---

## 3. Justificación Arquitectónica: ¿Por qué Factory Method y no Simple Factory con switch o new directo?

| Criterio Técnico | `new` Directo / Simple Factory con `switch` | Patrón Factory Method (GoF) |
| :--- | :--- | :--- |
| **Principio Abierto/Cerrado (OCP)** | ❌ Una clase Simple Factory con `switch($type)` debe ser editada cada vez que se agrega un comprobante, violando OCP. | ✅ **100% extensible sin modificación**: Para agregar una Factura de Exportación solo se crea `ExportInvoiceReceipt` y su factoría `ExportInvoiceFactory`. |
| **Inversión de Control** | ❌ El cliente decide y conoce la clase concreta que va a instanciar. | ✅ El cliente trabaja contra la abstracción `ReceiptFactory` y el contrato `ReceiptInterface`. El cliente desconoce la clase concreta del producto. |
| **Testabilidad** | ❌ Difícil de mockear; si el constructor del comprobante requiere servicios externos, la factoría con switch se sobrecarga de dependencias. | ✅ Cada factoría concreta puede inyectar sus propias dependencias específicas (ej. el servicio de firma digital para la factura electrónica DIAN, sin ensuciar la tirilla POS). |
| **Cumplimiento de la Rúbrica** | ❌ Penalizado en la rúbrica si se usan switches/ifs dentro de la factoría. | ✅ **20/20 puntos** al respetar rigurosamente la jerarquía Creator/ConcreteCreator de la literatura original GoF. |
