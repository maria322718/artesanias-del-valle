# Dimensión 1: Diagnóstico de Code Smells y Violaciones SOLID (60 Puntos)

## Matriz Formal de Auditoría de Arquitectura de Software

A continuación se detalla el diagnóstico riguroso realizado sobre el código original del sistema de comercio electrónico de artesanías colombianas (`legacy/LegacyCheckoutController.php`), correlacionando cada hallazgo con el catálogo canónico de **Code Smells de Martin Fowler**, los **Principios SOLID** y la solución arquitectónica implementada mediante **Patrones de Diseño Gang of Four (GoF)**.

| Archivo:Línea | Code Smell Detectado (Fowler) | Principio SOLID Violado | Impacto Negativo en Calidad y Mantenibilidad | Solución GoF Implementada |
| :--- | :--- | :--- | :--- | :--- |
| **`legacy/LegacyCheckoutController.php:36-37`** | **Inappropriate Intimacy / Primitive Obsession** | **DIP** (Dependency Inversion Principle) | Instanciación directa con `new` de clases concretas y SDKs bancarios de terceros. Imposibilita pruebas unitarias con mocks e impide cambiar el proveedor sin alterar la clase. | **Adapter + Inyección de Dependencias** (`LegacyBankAdapter`) |
| **`legacy/LegacyCheckoutController.php:43-178`** | **Long Method (Método Dios / God Object)** | **SRP** (Single Responsibility Principle) | Un único método de 135 líneas orquesta lectura HTTP, cálculo de subtotales, reglas de cobro, lógica de envíos, persistencia en BD, notificaciones por email y formateo de texto. Imposible de testear en aislamiento. | **Orquestador de Dominio (`CheckoutService`) + Observer** |
| **`legacy/LegacyCheckoutController.php:65-70`** | **Feature Envy (Envidia de Atributos)** | **SRP** / Abstracción de Dominio | El controlador itera y manipula manualmente los campos primitivos de arrays asociativos de ítems, calculando subtotales en lugar de delegar el cálculo a las entidades de negocio. | **Modelo de Dominio Rich (`Order` y `OrderItem`)** |
| **`legacy/LegacyCheckoutController.php:74-81`** | **Switch Statements (Cascada de `if/else`)** | **OCP** (Open/Closed Principle) | Se evalúan strings en bruto (`standard`, `express`, `artisan_pickup`). Agregar un nuevo tipo de envío (ej. Fluvial en el Amazonas o Mensajería Rural) requiere abrir y modificar el controlador. | **Strategy Pattern** |
| **`legacy/LegacyCheckoutController.php:83-93`** | **Combinatorial Explosion / Primitive Flags** | **OCP** & Composición | Banderas booleanas condicionales (`$wantsGiftWrap`, `$wantsInsurance`) calculando costos en duro. A medida que la tienda ofrezca certificado de autenticidad, curaduría o empaque prémium, se generará una explosión de condicionales. | **Decorator Pattern** (`GiftWrapDecorator`, `ArtisanInsuranceDecorator`) |
| **`legacy/LegacyCheckoutController.php:97-133`** | **Switch Statements / Duplicated Code** | **OCP** & **DIP** | Cascada de 36 líneas de `if/elseif` para procesar pagos (Tarjeta, PSE, PayPal, Legacy Bank). Agregar un nuevo medio de pago (ej. Nequi, Daviplata o Cripto) viola el principio Abierto/Cerrado al obligar a editar código en producción. | **Strategy Pattern** (`PaymentStrategyRegistry` + Estrategias Concretas) |
| **`legacy/LegacyCheckoutController.php:128-132`** | **Inappropriate Intimacy / Leaky Abstraction** | **DIP** (Dependency Inversion Principle) | El controlador conoce la estructura interna arcaica de una pasarela externa (`execute_transaction_v2` con parámetros `monto`, `COD_AUTH`), mezclando nombres en español/inglés y formatos no normalizados. | **Adapter Pattern** (`PaymentGatewayInterface` + `LegacyBankAdapter`) |
| **`legacy/LegacyCheckoutController.php:138-157`** | **Divergent Change / Mixed Concerns** | **SRP** (Single Responsibility Principle) | El controlador acopla directamente efectos secundarios posventa: descuento en base de datos, envío síncrono de correo electrónico (si el SMTP falla, la transacción del cliente crashea) y escritura de logs. | **Observer Pattern** (`AuditLogObserver`, `ArtisanNotificationObserver`, `StockReductionObserver`) |
| **`legacy/LegacyCheckoutController.php:160-176`** | **Conditional Document Creation** | **OCP** & **SRP** | Construcción manual de documentos mediante concatenación de cadenas dentro de un `if/else`. Incorporar Facturación Electrónica DIAN con formato XML/UBL 2.1 requiere alterar el flujo del checkout. | **Factory Method Pattern** (`ReceiptFactory`, `ElectronicInvoiceFactory`, `SimpleTicketFactory`) |

---

## Detalle Técnico de las Violaciones SOLID

### 1. Violación de SRP (Single Responsibility Principle)
El módulo `LegacyCheckoutController` tiene al menos **6 razones distintas para cambiar**:
1. Si cambian las reglas de validación de los datos del cliente.
2. Si cambian los porcentajes del seguro artesanal o el costo de empaque ecológico.
3. Si cambian los parámetros de la pasarela de pagos (PSE o Tarjeta).
4. Si cambia la plantilla del correo de alerta enviado a los artesanos.
5. Si cambia el esquema de auditoría en la base de datos.
6. Si la DIAN modifica la estructura técnica de la Factura Electrónica.

### 2. Violación de OCP (Open/Closed Principle)
El sistema legacy está **cerrado a la extensión y abierto a la modificación**. Para cualquier nuevo requerimiento comercial (nuevo método de pago, nuevo servicio de valor agregado para las artesanías, nuevo comprobante contable) el desarrollador debe ingresar a modificar el método `processCheckout`, con alto riesgo de regresión en funcionalidades existentes.

### 3. Violación de DIP (Dependency Inversion Principle)
Los módulos de alto nivel (el flujo de checkout de artesanías) dependen directamente de módulos de bajo nivel y librerías concretas (`new \Stripe\StripeClient()`, llamadas a métodos no estándar de bancos externos), en lugar de depender de abstracciones e interfaces del dominio.

### 4. Violación de ISP (Interface Segregation Principle)
El diseño original carece por completo de contratos e interfaces. Cuando se intentan definir interfaces en este esquema monolítico, terminan siendo interfaces gigantescas (fat interfaces) donde se fuerza a implementar métodos innecesarios para cada pasarela o comprobante.
