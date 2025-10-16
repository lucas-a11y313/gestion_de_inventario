# Explicación del InventarioInsumosController

Este documento explica línea por línea los métodos `insumosPrestados()` y `devolverInsumo()` del controlador `InventarioInsumosController.php`.

---

## Método `insumosPrestados()` (líneas 148-164)

Este método se encarga de mostrar todos los insumos que han sido prestados y aún no han sido devueltos.

### Código completo:

```php
public function insumosPrestados()
{
    // Obtener todas las solicitudes de préstamo que tienen al menos un insumo sin devolver
    $solicitudes = Solicitud::where('tipo_solicitud', 'prestamo')
        ->whereHas('productos', function ($query) {
            $query->where('productos.tipo', 'Insumo')
                ->whereNull('producto_solicitud.fecha_devolucion');
        })
        ->with(['user', 'productos' => function ($query) {
            // Solo cargar los insumos que no han sido devueltos
            $query->where('productos.tipo', 'Insumo')
                ->whereNull('producto_solicitud.fecha_devolucion');
        }])
        ->get();

    return view('InventarioInsumos.insumos_prestados', compact('solicitudes'));
}
```

### Explicación línea por línea:

#### Línea 148:
```php
public function insumosPrestados()
```
**Declaración del método público `insumosPrestados()`**
- No recibe parámetros
- Es llamado cuando el usuario accede a la ruta de "insumos prestados"
- Es público, por lo que puede ser accedido desde las rutas

#### Líneas 150-151:
```php
// Obtener todas las solicitudes de préstamo que tienen al menos un insumo sin devolver
$solicitudes = Solicitud::where('tipo_solicitud', 'prestamo')
```
- **Comentario explicativo** del propósito de la consulta
- `Solicitud::where('tipo_solicitud', 'prestamo')` - Inicia una consulta al modelo `Solicitud`
- Filtra solo las solicitudes donde el campo `tipo_solicitud` es igual a `'prestamo'`
- Excluye las solicitudes de tipo 'retiro'

#### Línea 152:
```php
->whereHas('productos', function ($query) {
```
**`whereHas()`** es un método de Laravel que filtra las solicitudes que tienen AL MENOS UN producto que cumpla con las condiciones dentro de la función anónima.
- Solo incluye solicitudes que tengan productos relacionados que cumplan ciertos criterios
- La función anónima `function ($query)` permite agregar condiciones a la relación

#### Línea 153:
```php
    $query->where('productos.tipo', 'Insumo')
```
**Filtro de tipo de producto**
- Dentro del `whereHas()`, filtra solo los productos donde el campo `tipo` es igual a `'Insumo'`
- Usamos `productos.tipo` para especificar explícitamente la tabla y evitar ambigüedades en el SQL
- Esto excluye productos de tipo 'BP' (Bien Patrimonial) u otros tipos

#### Línea 154:
```php
        ->whereNull('producto_solicitud.fecha_devolucion');
```
**Filtro de devolución**
- Añade otra condición: el campo `fecha_devolucion` debe ser `NULL` (no devuelto)
- Usamos `whereNull()` en lugar de `wherePivot()` porque estamos en un contexto de consulta SQL directa
- `producto_solicitud` es el nombre de la tabla pivot que relaciona productos con solicitudes
- Si `fecha_devolucion` es NULL, significa que el producto aún está prestado

#### Línea 155:
```php
})
```
Cierra la función anónima del `whereHas()`.

#### Línea 156:
```php
->with(['user', 'productos' => function ($query) {
```
**Eager Loading (carga anticipada)**
- `with()` carga las relaciones para evitar el problema N+1 (múltiples consultas innecesarias)
- `'user'` - Carga el usuario relacionado con cada solicitud (sin condiciones adicionales)
- `'productos' => function ($query)` - Carga los productos relacionados, pero con filtros específicos

**¿Por qué es necesario?**
- Sin `with()`, cada vez que accedas a `$solicitud->user` o `$solicitud->productos` se ejecutaría una nueva consulta a la base de datos
- Con `with()`, todas las relaciones se cargan en una sola consulta adicional

#### Líneas 157-158:
```php
    // Solo cargar los insumos que no han sido devueltos
    $query->where('productos.tipo', 'Insumo')
```
**Filtro en Eager Loading**
- Dentro del eager loading de productos, filtra para cargar SOLO los productos que sean de tipo 'Insumo'
- Este filtro se aplica a la colección `$solicitud->productos`

#### Línea 159:
```php
        ->whereNull('producto_solicitud.fecha_devolucion');
```
**Segundo filtro en Eager Loading**
- Filtra productos que tengan `fecha_devolucion` NULL (no devueltos)

**Nota importante:** Esta parte del `with()` es crucial porque aunque el `whereHas()` anterior ya filtró las solicitudes, aquí estamos filtrando QUÉ productos cargar en la colección `$solicitud->productos`. Sin esto, cargaría TODOS los productos de la solicitud, incluso los ya devueltos o los de tipo 'BP'.

#### Líneas 160-161:
```php
    }])
    ->get();
```
- Cierra el array del `with()`
- `->get()` ejecuta la consulta y devuelve una colección de solicitudes con sus relaciones cargadas
- El resultado es una colección de objetos `Solicitud` con sus propiedades `user` y `productos` ya cargadas

#### Línea 163:
```php
return view('InventarioInsumos.insumos_prestados', compact('solicitudes'));
```
**Retorno de la vista**
- `view()` - Función helper de Laravel para renderizar una vista Blade
- `'InventarioInsumos.insumos_prestados'` - Ruta a la vista: `resources/views/InventarioInsumos/insumos_prestados.blade.php`
- `compact('solicitudes')` - Convierte la variable `$solicitudes` en un array `['solicitudes' => $solicitudes]` que se pasa a la vista
- La vista puede acceder a `$solicitudes` directamente

#### Línea 164:
```php
}
```
Cierra el método.

---

## Método `devolverInsumo()` (líneas 166-187)

Este método procesa la devolución de un insumo prestado, actualizando el stock y registrando la fecha de devolución.

### Código completo:

```php
public function devolverInsumo(Request $request, $solicitud_id, $producto_id)
{
    try {
        $solicitud = Solicitud::findOrFail($solicitud_id);
        $producto = Producto::findOrFail($producto_id);

        // Update stock
        $pivot = $solicitud->productos()->where('producto_id', $producto->id)->first()->pivot;
        $cantidad_prestada = $pivot->cantidad;
        $producto->stock += $cantidad_prestada;
        $producto->save();

        // Update pivot table with return date
        $solicitud->productos()->updateExistingPivot($producto->id, [
            'fecha_devolucion' => now(),
        ]);

        return redirect()->route('insumos.prestados')->with('success', 'Insumo devuelto correctamente.');
    } catch (\Exception $e) {
        return redirect()->route('insumos.prestados')->with('error', 'Hubo un error al devolver el insumo: ' . $e->getMessage());
    }
}
```

### Explicación línea por línea:

#### Línea 166:
```php
public function devolverInsumo(Request $request, $solicitud_id, $producto_id)
{
```
**Declaración del método público `devolverInsumo()`**

Recibe 3 parámetros:
- `$request` - El objeto `Request` con todos los datos de la petición HTTP (headers, cookies, datos POST, etc.)
- `$solicitud_id` - ID de la solicitud (viene de la URL de la ruta como parámetro)
- `$producto_id` - ID del producto a devolver (viene de la URL de la ruta como parámetro)

**Ejemplo de URL:** `/insumos/devolver/2/1` donde `2` es el `$solicitud_id` y `1` es el `$producto_id`

#### Línea 168:
```php
try {
```
**Inicio del bloque try-catch**
- Inicia un bloque para manejar excepciones (errores)
- Si cualquier línea dentro del `try` lanza una excepción, el código salta inmediatamente al bloque `catch`
- Esto previene que la aplicación se rompa si algo sale mal

#### Línea 169:
```php
$solicitud = Solicitud::findOrFail($solicitud_id);
```
**Búsqueda de la solicitud**
- `Solicitud::findOrFail($solicitud_id)` - Busca una solicitud por su ID
- `findOrFail()` tiene dos comportamientos:
  - Si encuentra la solicitud: retorna el objeto `Solicitud`
  - Si NO la encuentra: lanza automáticamente una excepción `ModelNotFoundException` que Laravel convierte en un error 404
- Guarda el objeto `Solicitud` en la variable `$solicitud`

#### Línea 170:
```php
$producto = Producto::findOrFail($producto_id);
```
**Búsqueda del producto**
- Similar a la línea anterior
- Busca el producto usando `$producto_id`
- Si no existe, lanza un error 404
- Si existe, lo guarda en la variable `$producto`

#### Líneas 172-173:
```php
// Update stock
$pivot = $solicitud->productos()->where('producto_id', $producto->id)->first()->pivot;
```
**Obtención de datos del pivot**

Comentario: indica que vamos a actualizar el stock del producto

Desglose de la línea 173:
- `$solicitud->productos()` - Accede a la relación de productos **con paréntesis**
  - **Con paréntesis `()`**: Retorna un Query Builder (permite hacer consultas adicionales)
  - **Sin paréntesis**: Retornaría la colección ya cargada
- `->where('producto_id', $producto->id)` - Filtra para obtener solo el producto específico que estamos devolviendo
- `->first()` - Obtiene el primer (y único) resultado de la consulta
- `->pivot` - Accede a los datos de la tabla pivot `producto_solicitud`
- Guarda estos datos pivot en la variable `$pivot`

**¿Qué contiene `$pivot`?**
- `cantidad` - La cantidad prestada
- `precio_compra` - El precio de compra
- `fecha_devolucion` - La fecha de devolución (actualmente NULL)
- `created_at`, `updated_at` - Timestamps

#### Línea 174:
```php
$cantidad_prestada = $pivot->cantidad;
```
**Extracción de la cantidad**
- Extrae la cantidad prestada desde los datos del pivot
- La guarda en la variable `$cantidad_prestada`
- Esta cantidad está almacenada en la columna `cantidad` de la tabla `producto_solicitud`

**Ejemplo:** Si se prestaron 5 unidades, `$cantidad_prestada = 5`

#### Línea 175:
```php
$producto->stock += $cantidad_prestada;
```
**Actualización del stock**
- **Incrementa el stock** del producto sumándole la cantidad prestada
- El operador `+=` es equivalente a: `$producto->stock = $producto->stock + $cantidad_prestada`
- Esto devuelve el stock porque se estaba prestado y ahora regresa al inventario

**Ejemplo:**
- Stock actual: 10 unidades
- Cantidad prestada: 5 unidades
- Nuevo stock: 10 + 5 = 15 unidades

#### Línea 176:
```php
$producto->save();
```
**Persistencia en la base de datos**
- Guarda los cambios del producto en la base de datos
- Ejecuta un SQL UPDATE en la tabla `productos`
- **Sin este `save()`**, el cambio de stock solo estaría en memoria pero no se persistiría en la base de datos

#### Líneas 178-181:
```php
// Update pivot table with return date
$solicitud->productos()->updateExistingPivot($producto->id, [
    'fecha_devolucion' => now(),
]);
```
**Actualización de la fecha de devolución**

Comentario: indica que vamos a actualizar la tabla pivot con la fecha de devolución

Desglose:
- `$solicitud->productos()` - Accede nuevamente a la relación de productos (con paréntesis para usar Query Builder)
- `updateExistingPivot()` - Método de Laravel para actualizar registros en la tabla pivot
  - Primer parámetro: `$producto->id` - Identifica QUÉ producto actualizar en el pivot
  - Segundo parámetro: Array con los campos a actualizar
- `'fecha_devolucion' => now()` - Establece la fecha de devolución al momento actual
- `now()` - Helper de Laravel que retorna un objeto `Carbon` con la fecha y hora actual

**SQL generado (aproximadamente):**
```sql
UPDATE producto_solicitud
SET fecha_devolucion = '2025-10-16 14:30:00'
WHERE solicitud_id = 2 AND producto_id = 1
```

#### Línea 183:
```php
return redirect()->route('insumos.prestados')->with('success', 'Insumo devuelto correctamente.');
```
**Redirección con mensaje de éxito**

Si todo salió bien:
- `redirect()` - Helper de Laravel para redirigir al usuario a otra página
- `->route('insumos.prestados')` - Especifica que vaya a la ruta nombrada `'insumos.prestados'`
  - Laravel buscará la ruta con nombre `insumos.prestados` en `routes/web.php`
- `->with('success', '...')` - Añade un mensaje flash a la sesión
  - Clave: `'success'`
  - Valor: `'Insumo devuelto correctamente.'`
  - Este mensaje estará disponible en la siguiente petición y luego se eliminará automáticamente

**En la vista, puedes acceder al mensaje con:**
```blade
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

#### Línea 184:
```php
} catch (\Exception $e) {
```
**Captura de excepciones**
- Captura cualquier excepción que haya ocurrido dentro del bloque `try`
- `\Exception` - Clase base de todas las excepciones en PHP (el `\` indica que es del namespace global)
- `$e` - Variable que contiene el objeto de la excepción con información del error

**Posibles errores capturados:**
- Producto o solicitud no encontrados
- Error al guardar en la base de datos
- Error de validación
- Cualquier otro error inesperado

#### Línea 185:
```php
return redirect()->route('insumos.prestados')->with('error', 'Hubo un error al devolver el insumo: ' . $e->getMessage());
```
**Redirección con mensaje de error**

Si hubo un error:
- Redirige también a la ruta `'insumos.prestados'`
- `->with('error', ...)` - Añade un mensaje flash de error (clave: `'error'`)
- `$e->getMessage()` - Obtiene el mensaje descriptivo de la excepción para mostrarlo al usuario
- El operador `.` concatena el texto con el mensaje del error

**Ejemplo de mensaje:** "Hubo un error al devolver el insumo: Column not found"

#### Líneas 186-187:
```php
    }
}
```
- Línea 186: Cierra el bloque `catch`
- Línea 187: Cierra el método `devolverInsumo()`

---

## Resumen del flujo completo

### Flujo de `insumosPrestados()`:

1. **Consulta inicial:** Busca solicitudes de tipo "préstamo"
2. **Filtro whereHas:** Verifica que tengan al menos un insumo sin devolver
3. **Eager Loading:** Carga el usuario y solo los insumos no devueltos
4. **Retorno:** Pasa los datos a la vista `insumos_prestados.blade.php`
5. **Vista:** Muestra una tabla con los insumos prestados

### Flujo de `devolverInsumo()`:

1. **Recepción:** Recibe el ID de solicitud y producto desde la URL
2. **Validación:** Busca ambos registros en la base de datos (lanza 404 si no existen)
3. **Obtención de datos:** Lee la cantidad prestada desde la tabla pivot
4. **Actualización de stock:** Suma la cantidad prestada al stock actual del producto
5. **Persistencia de stock:** Guarda el nuevo stock en la base de datos
6. **Registro de devolución:** Marca la fecha actual en el campo `fecha_devolucion` del pivot
7. **Redirección:** Vuelve a la vista de insumos prestados con mensaje de éxito o error

---

## Conceptos importantes de Laravel utilizados

### 1. Eloquent ORM
Sistema de mapeo objeto-relacional de Laravel que permite trabajar con la base de datos usando objetos PHP en lugar de SQL directo.

### 2. Relaciones Many-to-Many (N a N)
- `Solicitud` y `Producto` tienen una relación muchos-a-muchos
- Se usa una tabla pivot `producto_solicitud` para conectarlas
- La tabla pivot puede tener columnas adicionales como `cantidad`, `precio_compra`, `fecha_devolucion`

### 3. Query Builder
Sistema de construcción de consultas que permite encadenar métodos para crear consultas SQL complejas.

### 4. whereHas()
Filtra el modelo principal basándose en condiciones de sus relaciones.

**Diferencia con `with()`:**
- `whereHas()` - Filtra qué registros principales traer
- `with()` - Filtra qué registros relacionados cargar (pero no afecta los principales)

### 5. Eager Loading (with)
Técnica para cargar relaciones de forma anticipada y evitar el problema N+1.

**Problema N+1:**
```php
// Malo: Hace 1 consulta + N consultas (una por cada solicitud)
$solicitudes = Solicitud::all();
foreach ($solicitudes as $solicitud) {
    echo $solicitud->user->name; // Nueva consulta por cada iteración
}

// Bueno: Hace solo 2 consultas (1 para solicitudes + 1 para todos los usuarios)
$solicitudes = Solicitud::with('user')->all();
foreach ($solicitudes as $solicitud) {
    echo $solicitud->user->name; // Sin consulta adicional
}
```

### 6. Flash Messages
Mensajes que se guardan en la sesión y están disponibles solo en la siguiente petición.

### 7. Route Model Binding
Laravel automáticamente convierte los IDs de la URL en instancias de modelos (cuando se usa en los parámetros del método del controlador).

---

## Tabla Pivot: producto_solicitud

### Estructura:
```
+---------------+----------+
| Campo         | Tipo     |
+---------------+----------+
| solicitud_id  | bigint   |
| producto_id   | bigint   |
| cantidad      | int      |
| precio_compra | decimal  |
| fecha_devolucion | timestamp (nullable) |
| created_at    | timestamp|
| updated_at    | timestamp|
+---------------+----------+
```

### Estados de un producto prestado:

1. **Prestado (sin devolver):** `fecha_devolucion = NULL`
2. **Devuelto:** `fecha_devolucion = '2025-10-16 14:30:00'`

---

## Notas adicionales

### ¿Por qué usar whereNull en lugar de wherePivot?

**En `whereHas()`:**
```php
// Correcto
->whereNull('producto_solicitud.fecha_devolucion')

// Incorrecto (causa error)
->wherePivot('fecha_devolucion', null)
```

**Razón:** `wherePivot()` solo funciona cuando ya estás trabajando directamente con la relación cargada, no en contextos de consulta SQL como `whereHas()`.

### ¿Por qué productos() con paréntesis?

```php
// Con paréntesis: Query Builder (permite hacer consultas)
$solicitud->productos()->where('producto_id', 1)->first()

// Sin paréntesis: Colección ya cargada
$solicitud->productos
```

### Manejo de errores robusto

El uso de `try-catch` asegura que:
1. La aplicación no se rompa si algo falla
2. El usuario reciba un mensaje descriptivo del error
3. Se pueda registrar el error en logs si es necesario

---

## ¿Por qué se filtra por `fecha_devolucion` DOS veces?

Esta es una de las dudas más comunes. Déjame explicarte con ejemplos concretos por qué necesitamos el filtro en ambos lugares.

### El problema: `whereHas()` y `with()` hacen cosas DIFERENTES

Imagina que tienes esta solicitud de préstamo:

**Solicitud ID: 5**
- Usuario: Juan Pérez
- Tipo: préstamo
- Productos:
  - **Producto A** (Insumo) - `fecha_devolucion = NULL` ❌ (NO devuelto)
  - **Producto B** (Insumo) - `fecha_devolucion = '2025-10-15'` ✅ (YA devuelto)
  - **Producto C** (BP) - `fecha_devolucion = NULL` ❌ (NO devuelto)

---

### Escenario 1: Solo con `whereHas()` (sin filtro en `with()`)

```php
$solicitudes = Solicitud::where('tipo_solicitud', 'prestamo')
    ->whereHas('productos', function ($query) {
        $query->where('productos.tipo', 'Insumo')
            ->whereNull('producto_solicitud.fecha_devolucion');
    })
    ->with(['user', 'productos']) // SIN FILTRO aquí
    ->get();
```

**Resultado:**
- ✅ La solicitud 5 SE INCLUYE (porque el `whereHas` verifica que tiene al menos UN insumo sin devolver: Producto A)
- ❌ PERO al usar `->with(['user', 'productos'])` SIN FILTRO, carga **TODOS** los productos asociados a esa solicitud: **A, B y C**

**En la vista verías:**
```
Solicitud #5 - Juan Pérez
  - Producto A (Insumo) - NO devuelto  ← Queremos ver esto
  - Producto B (Insumo) - Devuelto el 2025-10-15  ← NO queremos ver esto (ya devuelto)
  - Producto C (BP) - NO devuelto  ← NO queremos ver esto (es BP, no Insumo)
```

**Problema:** Muestra productos ya devueltos y productos de tipo BP.

---

### Escenario 2: Solo con filtro en `with()` (sin `whereHas()`)

```php
$solicitudes = Solicitud::where('tipo_solicitud', 'prestamo')
    // SIN whereHas aquí
    ->with(['user', 'productos' => function ($query) {
        $query->where('productos.tipo', 'Insumo')
            ->whereNull('producto_solicitud.fecha_devolucion');
    }])
    ->get();
```

Imaginemos otra solicitud:

**Solicitud ID: 8**
- Usuario: María López
- Tipo: préstamo
- Productos:
  - **Producto D** (Insumo) - `fecha_devolucion = '2025-10-10'` ✅ (YA devuelto)
  - **Producto E** (BP) - `fecha_devolucion = NULL` ❌ (NO devuelto)

**Resultado:**
- ✅ La solicitud 8 SE INCLUYE (porque es de tipo préstamo)
- ✅ Pero al cargar productos con el filtro, NO carga ninguno (porque ninguno cumple: D ya está devuelto y E es BP)

**En la vista verías:**
```
Solicitud #8 - María López
  (sin productos)  ← Solicitud vacía, no tiene sentido mostrarla
```

**Problema:** Muestra solicitudes vacías (sin productos relevantes).

---

### Escenario 3: CON AMBOS filtros (la solución correcta) ✅

```php
$solicitudes = Solicitud::where('tipo_solicitud', 'prestamo')
    ->whereHas('productos', function ($query) {  // FILTRO 1
        $query->where('productos.tipo', 'Insumo')
            ->whereNull('producto_solicitud.fecha_devolucion');
    })
    ->with(['user', 'productos' => function ($query) {  // FILTRO 2
        $query->where('productos.tipo', 'Insumo')
            ->whereNull('producto_solicitud.fecha_devolucion');
    }])
    ->get();
```

**Resultado para Solicitud 5:**
- ✅ La solicitud 5 SE INCLUYE (porque `whereHas` verifica que tiene al menos un insumo sin devolver)
- ✅ Solo carga el Producto A (porque `with` filtra los productos)

**En la vista verías:**
```
Solicitud #5 - Juan Pérez
  - Producto A (Insumo) - NO devuelto  ← Solo esto ✓
```

**Resultado para Solicitud 8:**
- ❌ La solicitud 8 NO SE INCLUYE (porque `whereHas` no encuentra insumos sin devolver)

**En la vista:** No aparece (correcto, porque no tiene insumos prestados actualmente)

---

### Resumen visual:

```
┌─────────────────────────────────────────────────────────────┐
│                    SOLICITUD DE PRÉSTAMO                     │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ whereHas() - DECIDE SI LA SOLICITUD SE INCLUYE       │  │
│  │ "¿Tiene al menos UN insumo sin devolver?"            │  │
│  │ - SÍ → Incluir la solicitud                          │  │
│  │ - NO → Descartar la solicitud                        │  │
│  └──────────────────────────────────────────────────────┘  │
│                           ↓                                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ with() - DECIDE QUÉ PRODUCTOS CARGAR                 │  │
│  │ "De todos los productos, ¿cuáles mostrar?"           │  │
│  │ Solo los insumos sin devolver                        │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

### Diferencias clave entre `whereHas()` y `with()`:

| Aspecto | `whereHas()` | `with()` |
|---------|--------------|----------|
| **Propósito** | Decidir QUÉ SOLICITUDES incluir | Decidir QUÉ PRODUCTOS cargar |
| **Pregunta** | "¿Esta solicitud tiene al menos un insumo sin devolver?" | "De todos los productos de esta solicitud, ¿cuáles cargo?" |
| **Efecto** | Si NO cumple → descarta toda la solicitud | Si NO cumple → no carga ese producto específico |
| **Nivel** | Nivel 1: Filtra registros principales (Solicitudes) | Nivel 2: Filtra registros relacionados (Productos) |
| **Carga datos** | ❌ NO carga productos, solo verifica | ✅ SÍ carga productos filtrados |

---

### ¿Por qué `whereHas()` NO carga los productos?

El `whereHas()` **NO carga productos**, solo hace una verificación en SQL:

```sql
-- SQL generado por whereHas()
SELECT * FROM solicitudes
WHERE tipo_solicitud = 'prestamo'
AND EXISTS (
    SELECT * FROM productos
    INNER JOIN producto_solicitud ON productos.id = producto_solicitud.producto_id
    WHERE solicitudes.id = producto_solicitud.solicitud_id
    AND productos.tipo = 'Insumo'
    AND producto_solicitud.fecha_devolucion IS NULL
)
```

**Este SQL pregunta:** "¿Existe al menos un insumo sin devolver?"
- Si SÍ → Incluye la solicitud en el resultado
- Si NO → Descarta la solicitud

**Pero NO trae los productos.** Solo verifica su existencia.

---

### `with()` SÍ carga los productos

```php
->with(['user', 'productos']) // Sin función de filtro
```

**Genera un SQL separado:**
```sql
-- SQL generado por with() sin filtro
SELECT * FROM productos
INNER JOIN producto_solicitud ON productos.id = producto_solicitud.producto_id
WHERE producto_solicitud.solicitud_id IN (5, 7, 9)
```

Este SQL trae **TODOS** los productos de las solicitudes incluidas, sin importar tipo ni fecha de devolución.

**Con filtro:**
```php
->with(['user', 'productos' => function ($query) {
    $query->where('productos.tipo', 'Insumo')
        ->whereNull('producto_solicitud.fecha_devolucion');
}])
```

**Genera:**
```sql
-- SQL generado por with() con filtro
SELECT * FROM productos
INNER JOIN producto_solicitud ON productos.id = producto_solicitud.producto_id
WHERE producto_solicitud.solicitud_id IN (5, 7, 9)
AND productos.tipo = 'Insumo'
AND producto_solicitud.fecha_devolucion IS NULL
```

Ahora sí solo trae los insumos sin devolver.

---

### Analogía del restaurante 🍽️

Imagina que estás buscando **restaurantes que tengan al menos un plato vegetariano**:

#### `whereHas()` = Filtro de restaurantes
```
"¿Este restaurante tiene al menos un plato vegetariano en el menú?"
- Restaurante A: SÍ (tiene ensalada) → Lo incluimos en la lista
- Restaurante B: NO (solo carnes) → Lo descartamos
```

#### `with()` = Qué del menú te traen
```
Una vez que entraste al Restaurante A:
- Sin filtro en with(): Te traen TODO el menú completo
  → Ensalada (vegetariano) ✓
  → Hamburguesa (no vegetariano) ✗
  → Pizza (no vegetariana) ✗

- Con filtro en with(): Solo te traen los platos vegetarianos
  → Ensalada (vegetariano) ✓
```

**Conclusión:** Necesitas ambos filtros:
1. `whereHas()` para entrar solo a restaurantes con opciones vegetarianas
2. `with()` con filtro para que solo te muestren las opciones vegetarianas del menú

---

### En resumen:

**¿Por qué se filtra DOS veces?**

Porque `whereHas()` y `with()` trabajan en **niveles diferentes**:

1. **Nivel 1 (`whereHas`):** Filtra qué solicitudes completas traer de la base de datos
2. **Nivel 2 (`with`):** Filtra qué productos de cada solicitud cargar

**Sin ambos filtros:** Obtendrías solicitudes vacías o con productos incorrectos.

**Con ambos filtros:** Solo ves solicitudes que tienen insumos sin devolver, y solo ves esos insumos específicos.

---

**Fecha de creación:** 16 de Octubre de 2025
**Autor:** Documentación generada para el proyecto Sistema Inventario IoT
**Versión de Laravel:** 11.46.1
