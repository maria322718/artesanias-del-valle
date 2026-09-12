# 🏺 Artesanías del Valle — Arquitectura de Software, SOLID y Patrones GoF

> **Proyecto Final Integrador — Especialización en Desarrollo de Software**  
> **Repositorio de referencia**: `miguepoloc/especializacion_ds`  
> **Puntaje Objetivo en Rúbrica**: **350 / 350 Puntos**  
> (Dimensión 1: 60 pts | Dimensión 2: 100 pts | Dimensión 3: 50 pts | Dimensión 4: 60 pts | Dimensión 5: 80 pts)

---

## 📋 Resumen Ejecutivo del Proyecto

**"Artesanías del Valle"** es una plataforma de comercio electrónico orientada a la venta directa de artesanías tradicionales colombianas (Sombrero Vueltiao de Tuchín, Mochilas Wayuu de La Guajira, Cerámica Negra de Ráquira, Chivas de Pitalito, Barniz de Pasto y Hamacas de San Jacinto).

El proyecto fue diseñado y refactorizado desde un controlador monolítico legado (`legacy/LegacyCheckoutController.php`, >180 líneas de código espagueti) hacia una arquitectura limpia y desacoplada basada en **PHP 8.2+**, **Laravel**, **PostgreSQL** y **Docker Compose (Sail)**, implementando estrictamente **5 Patrones de Diseño Gang of Four (GoF)** y los **5 Principios SOLID**.

---

## 🎯 Cumplimiento Detallado de la Rúbrica de Evaluación (350 Pts)

### Dimensión 1: Diagnóstico del Código "Antes" (60 Puntos)
- Ubicación del código espagueti: [`legacy/LegacyCheckoutController.php`](legacy/LegacyCheckoutController.php) (con números de línea fijos y auditables).
- Documento formal de diagnóstico: [`docs/sustentacion/01_diagnostico_code_smells.md`](docs/sustentacion/01_diagnostico_code_smells.md).
- **Matriz de Code Smells identificados con citas exactas `archivo:línea`**:
  1. `LegacyCheckoutController:36-37`: **Inappropriate Intimacy / Primitive Obsession** (Violación **DIP**). Instanciación directa con `new` de SDKs bancarios externos.
  2. `LegacyCheckoutController:43-178`: **Long Method / God Object** (Violación **SRP**). 135 líneas mezclando HTTP, cálculos, cobros, base de datos, correos síncronos y logs.
  3. `LegacyCheckoutController:65-70`: **Feature Envy** (Violación **SRP** / Dominio anémico). Manipulación directa de arrays asociativos de productos.
  4. `LegacyCheckoutController:74-81`: **Switch Statements / Cascada if/else** (Violación **OCP**). Lógica rígida para tipos de envío.
  5. `LegacyCheckoutController:83-93`: **Combinatorial Explosion / Primitive Flags** (Violación **OCP**). Banderas booleanas que para $N$ opciones causarían $2^N$ clases si se usara herencia.
  6. `LegacyCheckoutController:97-133`: **Switch Statements / Duplicated Logic** (Violación **OCP** y **DIP**). 36 líneas de `if/elseif` para procesar pagos.
  7. `LegacyCheckoutController:128-132`: **Leaky Abstraction / Incompatible Interface** (Violación **DIP**). Acoplamiento con método arcaico `execute_transaction_v2`.
  8. `LegacyCheckoutController:138-157`: **Divergent Change / Mixed Concerns** (Violación **SRP**). Efectos secundarios posventa ejecutados en el hilo principal.
  9. `LegacyCheckoutController:160-176`: **Conditional Document Creation** (Violación **OCP** y **SRP**). Concatenación manual de comprobantes en un `if/else`.

---

### Dimensión 2: Implementación de los 5 Patrones GoF (100 Puntos - 20 pts c/u)
Cada patrón resuelve un problema exacto del diagnóstico y cumple rigurosamente los roles canónicos de diseño (prohibido switch/case en factorías):

1. **Patrón Strategy (Comportamiento) — app/Patterns/Strategy/**
   - Contrato: `PaymentStrategyInterface`.
   - Clases concretas: `CreditCardPaymentStrategy`, `PSEPaymentStrategy`, `PaypalPaymentStrategy`.
   - Resolución en $O(1)$ sin condicionales mediante `PaymentStrategyRegistry`.
2. **Patrón Factory Method (Creacional) — app/Patterns/FactoryMethod/**
   - Creador abstracto: `ReceiptFactory` con método abstracto `createReceipt(Order $order): ReceiptInterface` y template method `generateDocument()`.
   - Creadores concretos: `ElectronicInvoiceFactory` (crea `ElectronicInvoiceReceipt` con CUFE DIAN) y `SimpleTicketFactory` (crea `SimpleTicketReceipt` para feria).
   - **Cero condicionales `switch/if` dentro de las factorías**: Creación 100% polimórfica por subclase.
3. **Patrón Observer (Comportamiento) — app/Patterns/Observer/**
   - Sujeto observable: `OrderSubject` que implementa `OrderSubjectInterface`.
   - Observadores suscritos: `AuditLogObserver` (persiste en `audit_logs`), `ArtisanNotificationObserver` (simula alerta al taller del artesano) y `StockReductionObserver` (decremento atómico de inventario).
4. **Patrón Decorator (Estructural) — app/Patterns/Decorator/**
   - Componente base: `OrderCostInterface` y `BaseOrderCost`.
   - Decorador abstracto: `OrderCostDecorator implements OrderCostInterface`.
   - Decoradores concretos: `GiftWrapDecorator` (+ $15,000 COP / canasto ecológico de mimbre) y `ArtisanInsuranceDecorator` (+ 5% / seguro contra rotura de piezas frágiles de cerámica).
   - Apilamiento dinámico en tiempo de ejecución (composición sobre herencia).
5. **Patrón Adapter (Estructural) — app/Patterns/Adapter/**
   - Target Interface: `PaymentGatewayInterface::processPayment(float $amount, string $currency, array $metadata): GatewayResponse`.
   - Adaptee Incompatible: `ExternalLegacyBankGateway::execute_transaction_v2(array $payload)`.
   - Adapter: `LegacyBankAdapter implements PaymentGatewayInterface`, traduciendo firma, divisas y códigos de respuesta.

---

### Dimensión 3: Justificación de cada Patrón (50 Puntos - 10 pts c/u)
Documentos técnicos listos para diapositivas con tablas comparativas y fragmentos ANTES vs DESPUÉS:
- [`docs/sustentacion/02_patron_strategy.md`](docs/sustentacion/02_patron_strategy.md): Por qué Strategy y por qué NO un if/else (beneficio OCP y testabilidad).
- [`docs/sustentacion/03_patron_factory_method.md`](docs/sustentacion/03_patron_factory_method.md): Por qué Factory Method y no `new` directo ni Simple Factory con switch.
- [`docs/sustentacion/04_patron_observer.md`](docs/sustentacion/04_patron_observer.md): Desacoplamiento de eventos posventa (SRP) y prevención de fallas en cascada.
- [`docs/sustentacion/05_patron_decorator.md`](docs/sustentacion/05_patron_decorator.md): Composición sobre herencia y cálculo matemático de prevención de explosión combinatoria ($2^N$).
- [`docs/sustentacion/06_patron_adapter.md`](docs/sustentacion/06_patron_adapter.md): Aislamiento de API de terceros y patrón Anti-Corruption Layer (DIP).

---

### Dimensión 4: Calidad del Código Refactorizado (60 Puntos)
- Tipado estricto en el 100% de archivos PHP: `declare(strict_types=1);`.
- Suite automatizada de **5 pruebas unitarias** en `tests/Unit/` demostrando el Principio de Sustitución de Liskov (LSP):
  1. `StrategyPatternTest.php`: Verifica que todas las estrategias sean intercambiables bajo `PaymentStrategyInterface` y devuelvan `PaymentResult`.
  2. `FactoryMethodPatternTest.php`: Verifica la creación polimórfica de comprobantes y extracción de metadatos fiscales DIAN.
  3. `ObserverPatternTest.php`: Verifica suscripción, desuscripción y difusión sin acoplamiento a los 3 observadores.
  4. `DecoratorPatternTest.php`: Verifica apilamiento dinámico independiente del orden (Base + Wrap + Insurance).
  5. `AdapterPatternTest.php`: Verifica la traducción de parámetros y códigos de error del SDK bancario arcaico.
- Migraciones y Seeders con 6 artesanías colombianas auténticas (Boyacá, Córdoba, Guajira, Huila, Nariño, Bolívar).

---

### Dimensión 5: Sustentación Oral (80 Puntos)
- Documento: [`docs/sustentacion/07_guion_sustentacion_oral.md`](docs/sustentacion/07_guion_sustentacion_oral.md).
- **Banco de 10 preguntas trampa de evaluadores y jurados**, cubriendo:
  1. Simple Factory con switch vs Factory Method canónico GoF.
  2. Garantía formal del Principio de Sustitución de Liskov (LSP).
  3. Resolución de estrategias sin switch en tiempo constante $O(1)$.
  4. Interfaces explícitas vs Eventos mágicos de Laravel.
  5. Explosión combinatoria ($2^N$) evitada por Decorator.
  6. Diferencia técnica fundamental entre Adapter, Facade y Proxy.
  7. Transparencia de interfaces en decoradores anidados.
  8. Prevención de condiciones de carrera (Race Conditions) con transacciones ACID y pesimistas.
  9. Refactorizaciones formales de Martin Fowler aplicadas.
  10. Localización de cambios ante nuevos requerimientos de negocio.

---

## 🚀 Guía de Instalación y Ejecución

### Opción A: Ejecución con Docker (Laravel Sail + PostgreSQL 15) — Recomendada

1. Clona o ubícate en el directorio del proyecto:
   ```bash
   cd artesanias-del-valle
   ```

2. Copia las variables de entorno para Docker Sail (PostgreSQL):
   ```bash
   cp .env.example .env
   ```

3. Levanta los contenedores en segundo plano:
   ```bash
   ./vendor/bin/sail up -d
   # O en Windows si usas docker-compose directamente:
   docker compose up -d
   ```

4. Ejecuta las migraciones y puebla el catálogo con las 6 artesanías colombianas:
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

5. Ejecuta la suite de pruebas unitarias de los 5 patrones GoF:
   ```bash
   ./vendor/bin/sail test --testsuite=Unit
   ```

6. Abre tu navegador en:
   - **Tienda y Catálogo**: `http://localhost`
   - **Inspector de Arquitectura (Rúbrica 350)**: `http://localhost/arquitectura-solid-gof`

---

### Opción B: Ejecución Rápida Local (Con Fallback a SQLite)

Si no tienes Docker Desktop iniciado en Windows, puedes ejecutar el proyecto directamente con PHP y SQLite:

1. Asegúrate de que `.env` tenga configurado SQLite:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

2. Ejecuta las migraciones y seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

3. Corre las pruebas unitarias:
   ```bash
   php artisan test --testsuite=Unit
   ```

4. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```
   Accede en `http://127.0.0.1:8000`.

---

## 📂 Estructura del Repositorio

```
artesanias-del-valle/
├── legacy/                                     # DIMENSIÓN 1: CÓDIGO "ANTES"
│   └── LegacyCheckoutController.php           # Monolito espagueti con citas exactas de línea
├── docs/sustentacion/                         # DIMENSIONES 3 Y 5: SUSTENTACIÓN
│   ├── 01_diagnostico_code_smells.md          # Matriz de smells, SOLID e impacto
│   ├── 02_patron_strategy.md                  # Antes vs Después + Justificación OCP
│   ├── 03_patron_factory_method.md            # Antes vs Después + Justificación Creacional
│   ├── 04_patron_observer.md                  # Antes vs Después + Justificación SRP
│   ├── 05_patron_decorator.md                 # Antes vs Después + Justificación Composición
│   ├── 06_patron_adapter.md                   # Antes vs Después + Justificación DIP/ACL
│   └── 07_guion_sustentacion_oral.md          # 10 preguntas trampa con respuestas de defensa
├── app/
│   ├── Patterns/                              # DIMENSIÓN 2: 5 PATRONES GOF
│   │   ├── Strategy/                          # Strategy: CreditCard, PSE, PayPal, Registry
│   │   ├── FactoryMethod/                     # Factory Method: ReceiptFactory, DIAN Invoice, Ticket
│   │   ├── Observer/                          # Observer: OrderSubject, AuditLog, Artisan, Stock
│   │   ├── Decorator/                         # Decorator: BaseOrderCost, GiftWrap, ArtisanInsurance
│   │   └── Adapter/                           # Adapter: PaymentGatewayInterface, LegacyBankAdapter
│   ├── Services/                              # Orquestador de Checkout desacoplado
│   ├── Models/                                # Product, Order, OrderItem, AuditLog
│   └── Http/Controllers/                      # Catalog, Cart, Checkout, ArchitectureReview
├── tests/Unit/                                # DIMENSIÓN 4: 5 PRUEBAS UNITARIAS + LSP
├── database/                                  # Migraciones PostgreSQL/SQLite y Seeders
├── docker-compose.yml                         # Laravel Sail (PHP 8.2 + PostgreSQL 15)
└── README.md
```
