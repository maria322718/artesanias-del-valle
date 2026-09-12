# Dimensión 5: Guión de Sustentación Oral y Banco de Preguntas Trampa (80 Puntos)

Este documento es una guía de preparación de alto nivel técnico para la defensa oral del proyecto **"Artesanías del Valle"**. Contiene las **10 preguntas trampa más difíciles** que suelen formular jurados y evaluadores de arquitectura de software, acompañadas de las respuestas canónicas fundamentadas en la literatura de **Gang of Four (GoF)**, **Martin Fowler (Refactoring)** y **Robert C. Martin (Clean Architecture)**.

---

### Pregunta 1: "¿Por qué crearon clases abstractas y creadores concretos para Factory Method en lugar de un `Simple Factory` con un `switch($tipo)` que tiene menos líneas de código?"

> **Respuesta Técnica de Defensa:**
> "Profesor, utilizar un `switch($tipo)` dentro de una clase factoría estática corresponde al modismo conocido como *Simple Factory* o *Static Factory*, el cual **no es uno de los 23 patrones canónicos del Gang of Four** y, lo más grave, **viola directamente el Principio Abierto/Cerrado (OCP)** de SOLID. 
> Cada vez que la tienda incorpore un nuevo formato tributario (por ejemplo, una Factura de Exportación para compras internacionales o un Certificado de Donación a Resguardos Indígenas), una factoría con `switch` tendría que ser abierta y modificada en su código fuente, introduciendo riesgo de regresión en los tipos de factura existentes.
> Con el patrón canónico **Factory Method**, la clase creadora `ReceiptFactory` define una operación plantilla y delega la instanciación al polimorfismo de sus subclases (`ElectronicInvoiceFactory` y `SimpleTicketFactory`). Si mañana se crea un nuevo comprobante, únicamente agregamos una nueva subclase sin tocar una sola línea de código en producción, respetando al 100% OCP y asegurando la rúbrica al no usar condicionales en la creación."

---

### Pregunta 2: "¿Cómo garantiza su diseño el Principio de Sustitución de Liskov (LSP) en la jerarquía de las estrategias de pago?"

> **Respuesta Técnica de Defensa:**
> "El Principio de Sustitución de Liskov establece que los objetos de un programa deben poder ser reemplazados por instancias de sus subtipos sin alterar la corrección del programa. En nuestro diseño lo garantizamos de tres formas estrictas:
> 1. **Misma firma y tipos estrictos (`declare(strict_types=1);`)**: Todas las estrategias implementan `PaymentStrategyInterface` con la firma exacta `pay(float $amount, array $paymentDetails): PaymentResult`. Ninguna subclase altera los tipos de entrada ni de salida.
> 2. **Precondiciones y Postcondiciones**: Ninguna estrategia endurece las precondiciones (por ejemplo, exigir parámetros absurdos que la interfaz no contempla) ni debilita las postcondiciones. Todas retornan invariablemente un objeto de valor inmutable `PaymentResult`.
> 3. **Sin efectos colaterales inesperados**: Ninguna estrategia lanza excepciones no verificadas para flujos de negocio normales. Los rechazos de tarjeta o saldo insuficiente en PSE se modelan como estados controlados (`$result->isSuccessful() === false`), permitiendo al servicio cliente procesar cualquier estrategia de manera completamente intercambiable."

---

### Pregunta 3: "¿En el patrón Strategy, cómo seleccionan la estrategia correcta en tiempo de ejecución sin caer en un `switch` o `if/else` escondido?"

> **Respuesta Técnica de Defensa:**
> "Excelente observación. En lugar de un `switch`, implementamos el patrón **Registry / Dependency Injection Container Map**. 
> Cada estrategia se auto-identifica con un identificador único mediante el método `getMethodCode()` (por ejemplo: `'credit_card'`, `'pse'`, `'paypal'`, `'legacy_bank'`).
> Durante el arranque del framework en el Service Provider, el `PaymentStrategyRegistry` mapea y almacena las instancias en una tabla asociativa clave-valor inyectada por el contenedor de Laravel.
> Cuando el cliente envía su solicitud, el servicio invoca `$registry->get($methodCode)`. La resolución se realiza en tiempo constante $O(1)$ mediante búsqueda de clave en la colección de dependencias registradas, garantizando una arquitectura abierta a la extensión sin bifurcaciones condicionales."

---

### Pregunta 4: "¿Por qué no utilizaron simplemente los Events y Listeners nativos de Laravel en lugar de programar explícitamente `OrderSubject` y `OrderObserverInterface`?"

> **Respuesta Técnica de Defensa:**
> "Aunque los eventos nativos de Laravel internamente implementan una variante del patrón Observer, depender ciegamente de ellos en el núcleo del dominio genera dos problemas arquitectónicos:
> 1. **Violación de Arquitectura Limpia y DIP**: El dominio de negocio quedaría acoplado al framework y al façade global `Event::dispatch()`, lo cual dificulta portar el núcleo de negocio o probarlo fuera del ciclo de vida HTTP de Laravel.
> 2. **Rigor Académico en la Rúbrica GoF**: La rúbrica evalúa el entendimiento de los roles canónicos del patrón: Sujeto Concreto (`OrderSubject`), Sujeto Abstracto (`OrderSubjectInterface`), Observador Abstracto (`OrderObserverInterface`) y Observadores Concretos (`AuditLogObserver`, `ArtisanNotificationObserver`, `StockReductionObserver`). 
> Al diseñar las interfaces canónicas explícitas, el código demuestra dominio teórico de GoF y, simultáneamente, nuestro `OrderSubject` puede apoyarse en la infraestructura asíncrona de Laravel sin perder su pureza de diseño."

---

### Pregunta 5: "¿Qué problema genera el patrón Decorator con respecto a la explosión de clases y por qué es superior a la herencia en el cálculo de costos de artesanías?"

> **Respuesta Técnica de Defensa:**
> "Si hubiéramos intentado resolver los servicios adicionales de la tienda mediante herencia clásica, nos habríamos enfrentado a la **explosión combinatoria de subclases**: para $N$ servicios opcionales independientes (empaque de mimbre, seguro contra daños en cerámica, certificado de autenticidad Wayuu, envío prioritario), el número de clases necesarias es $2^N$. Para 4 opciones necesitaríamos 16 subclases distintas (`OrderWithWrap`, `OrderWithInsurance`, `OrderWithWrapAndInsurance`, etc.).
> Con el patrón **Decorator**, solo requerimos $N$ clases de decoradores (1 por servicio). En tiempo de ejecución, componemos los objetos envolviéndolos dinámicamente:
> `$cost = new ArtisanInsuranceDecorator(new GiftWrapDecorator(new BaseOrderCost($subtotal)))`.
> Esto cumple fielmente la máxima de diseño de GoF: *'Favorecer la composición de objetos sobre la herencia de clases'*, permitiendo activar, desactivar o añadir decoradores sin alterar las clases base."

---

### Pregunta 6: "¿Cuál es la diferencia técnica fundamental entre el patrón Adapter y el patrón Facade, y por qué aquí correspondía un Adapter?"

> **Respuesta Técnica de Defensa:**
> "La diferencia radica en la **intención del diseño**:
> - **Facade** tiene como intención proporcionar una interfaz simplificada de alto nivel para un subsistema complejo de múltiples clases, pero no traduce una interfaz preexistente para hacerla compatible con otra.
> - **Adapter** tiene como intención específica convertir la interfaz de una clase existente e incompatible (`ExternalLegacyBankGateway::execute_transaction_v2(array)`) en otra interfaz esperada por el cliente (`PaymentGatewayInterface::processPayment(float, string, array)`).
> En nuestro caso, el dominio de 'Artesanías del Valle' ya poseía su propio contrato estandarizado de pagos. La librería externa del banco arcaico poseía métodos con nombres en español, parámetros en arrays heterogéneos y retornos con códigos crudos. Por lo tanto, el problema era de **incompatibilidad de firmas de interfaz**, lo cual es la definición exacta del patrón **Adapter** actuando como Capa Anticorrupción (ACL)."

---

### Pregunta 7: "¿Si un decorador necesita acceder a un método propio del componente concreto interno que no está en la interfaz común, qué principio se rompe?"

> **Respuesta Técnica de Defensa:**
> "Se rompería tanto el principio de diseño del patrón **Decorator** como el **Principio de Inversión de Dependencias (DIP)** y el **Principio de Sustitución de Liskov (LSP)**. 
> El patrón Decorator exige que tanto el componente base como los decoradores compartan la misma interfaz idéntica (`OrderCostInterface`). Si un decorador asume o fuerza un casting para invocar métodos privados o particulares del componente concreto, rompe la transparencia: el decorador ya no podría envolver a otro decorador de manera anidada y transparente.
> En nuestra solución, todos los métodos requeridos (`calculateTotal()` y `getBreakdown()`) forman parte del contrato común, asegurando que cualquier decorador pueda envolver a otro en cualquier orden sin conocer los detalles de implementación interna."

---

### Pregunta 8: "¿Cómo evita su implementación del `StockReductionObserver` condiciones de carrera (race conditions) si dos compradores compran la última Mochila Wayuu al mismo tiempo?"

> **Respuesta Técnica de Defensa:**
> "Excelente punto de concurrencia. En el controlador legacy original (`LegacyCheckoutController.php:141`), el decremento se hacía con una lectura y escritura no protegida, susceptible a venta en negativo.
> En nuestro `StockReductionObserver`, implementamos dos capas de protección:
> 1. **Transacciones Atómicas de Base de Datos**: La operación se ejecuta dentro de una transacción `DB::transaction()` con bloqueo pesimista mediante `lockForUpdate()` sobre la fila del producto en PostgreSQL.
> 2. **Restricción a Nivel de Motor (Constraint CHECK)**: En la migración de la base de datos se declara la regla de que el campo `stock` debe ser un entero no negativo (`stock >= 0`). Si el stock llega a 0 y una transacción concurrente intenta descontar, la base de datos aborta la transacción y el observador arroja una excepción de dominio `InsufficientStockException`, manteniendo la consistencia ACID."

---

### Pregunta 9: "¿Qué refactorizaciones específicas del catálogo de Martin Fowler se aplicaron para desmantelar el controlador espagueti?"

> **Respuesta Técnica de Defensa:**
> "Del catálogo canónico de Martin Fowler aplicamos 5 refactorizaciones formales:
> 1. **Extract Class / Move Method**: Extrajimos la lógica de checkout fuera del controlador HTTP hacia el servicio de dominio `CheckoutService`.
> 2. **Replace Conditional with Polymorphism**: Reemplazamos la cascada de `if/else` de pagos por el patrón **Strategy**, y los condicionales de facturación por **Factory Method**.
> 3. **Introduce Parameter Object**: Los arrays asociativos no tipados se reemplazaron por objetos de valor inmutables como `PaymentResult`, `OrderCostBreakdown` y `ReceiptData`.
> 4. **Encapsulate Collection / Replace Primitive with Object**: Los ítems del pedido dejaron de ser arrays en bruto y se convirtieron en entidades ricas `OrderItem` asociadas a `Order`.
> 5. **Separate Query from Modifier**: Desacoplamos las consultas de cálculo de totales de las mutaciones de inventario y auditoría delegándolas a observadores."

---

### Pregunta 10: "Si el negocio decide mañana que los pedidos mayores a $500,000 COP tienen envío gratis y seguro de transporte sin costo, ¿qué archivos tendría que modificar en su arquitectura?"

> **Respuesta Técnica de Defensa:**
> "Gracias a la arquitectura desacoplada:
> 1. **No se modifica el controlador HTTP ni los modelos de base de datos.**
> 2. Simplemente se modifica o extiende la clase concreta `ArtisanInsuranceDecorator` para verificar si el total base del componente decorado supera el umbral ($500,000 COP) y, en tal caso, retornar costo $0.0 con la descripción 'Seguro de cortesía para artesanos'.
> 3. De igual manera, si el envío gratis se maneja como una estrategia, se ajusta la estrategia de envío o se agrega una regla de negocio en el servicio orquestador.
> Esto demuestra que el impacto del cambio está perfectamente **localizado en una sola clase** (alta cohesión) sin propagar ondas de choque al resto del sistema."
