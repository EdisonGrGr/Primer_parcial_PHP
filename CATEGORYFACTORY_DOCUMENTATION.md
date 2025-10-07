# 📋 CategoryFactory - Documentación Completa

## 🎯 Punto 7.1 - Factory para el Modelo de Categorías

El **CategoryFactory** ha sido implementado exitosamente para generar datos de prueba realistas para el modelo `Category`. Este factory sigue las mejores prácticas de Laravel y proporciona múltiples estados para diferentes escenarios de testing.

---

## 🏗️ Estructura del Factory

### 📊 Campos Generados

El CategoryFactory genera datos para todos los campos del modelo Category:

| Campo | Tipo | Faker Method | Descripción |
|-------|------|--------------|-------------|
| `name` | string(100) | randomElement() | Categorías realistas de vehículos |
| `description` | text nullable | boolean(85) | Descripción detallada (85% probabilidad) |
| `priority` | integer | numberBetween(1, 10) | Prioridad de 1 a 10 |
| `discount_percentage` | decimal(5,2) | randomFloat(2, 0, 25.00) | Descuento del 0% al 25% |
| `estado` | boolean | boolean(90) | 90% probabilidad de estar activo |
| `created_date` | date | dateTimeBetween() | Fecha entre hace 2 años y hoy |

### 🚗 Categorías Realistas

El factory utiliza categorías de vehículos realistas:

- **Sedán** - Vehículos tipo sedán de 4 puertas
- **SUV** - Vehículos utilitarios deportivos de gran tamaño  
- **Hatchback** - Vehículos compactos con portón trasero
- **Coupe** - Vehículos deportivos de 2 puertas
- **Pickup** - Camionetas con caja de carga
- **Convertible** - Vehículos con techo retráctil
- **Wagon** - Vehículos familiares con amplio espacio de carga
- **Crossover** - Vehículos híbridos entre sedán y SUV
- **Minivan** - Vehículos familiares de gran capacidad
- **Deportivo** - Vehículos de alto rendimiento y velocidad

---

## 🎨 Estados Disponibles

### 1. Estado Básico
```php
$category = Category::factory()->make();
// Genera una categoría con datos aleatorios
```

### 2. Estado Activo
```php
$category = Category::factory()->active()->create();
// Garantiza que estado = true
```

### 3. Estado Inactivo
```php
$category = Category::factory()->inactive()->create();
// Garantiza que estado = false
```

### 4. Estado Premium
```php
$category = Category::factory()->premium()->create();
// Prioridad alta (1-3), descuento alto (15-25%), activo
```

### 5. Estado Básico (Sin extras)
```php
$category = Category::factory()->basic()->create();
// Prioridad baja (7-10), sin descuento, sin descripción
```

### 6. Nombre Personalizado
```php
$category = Category::factory()->withName('Mi Categoría')->create();
// Establece un nombre específico
```

---

## 📚 Ejemplos de Uso

### 🔧 Uso Básico

```php
// Generar una categoría (sin guardar)
$category = Category::factory()->make();

// Crear y guardar una categoría
$category = Category::factory()->create();

// Crear múltiples categorías
$categories = Category::factory()->count(5)->create();
```

### 🎯 Estados Específicos

```php
// Crear categorías con estados específicos
$activeCategory = Category::factory()->active()->create();
$premiumCategory = Category::factory()->premium()->create();
$customCategory = Category::factory()->withName('Lujo')->create();

// Combinar estados
$premiumActive = Category::factory()
    ->premium()
    ->active()
    ->create();
```

### 📦 Uso en Lotes

```php
// Crear 10 categorías activas
$activeCategories = Category::factory()
    ->active()
    ->count(10)
    ->create();

// Crear mix de categorías
$mixedCategories = collect([
    Category::factory()->premium()->count(2)->create(),
    Category::factory()->basic()->count(3)->create(),
    Category::factory()->active()->count(5)->create(),
])->flatten();
```

### 🧪 Uso en Testing

```php
// En un test
public function test_category_creation()
{
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'estado' => true
    ]);
    
    $this->assertDatabaseHas('categories', [
        'name' => 'Test Category',
        'estado' => true
    ]);
}

// Testing con estados
public function test_premium_categories_have_high_discount()
{
    $category = Category::factory()->premium()->create();
    
    $this->assertGreaterThanOrEqual(15.00, $category->discount_percentage);
    $this->assertLessThanOrEqual(25.00, $category->discount_percentage);
}
```

---

## 🔗 Integración con Seeders

### CategorySeeder Ejemplo

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Crear categorías básicas del sistema
        $basicCategories = [
            ['name' => 'Sedán', 'priority' => 1],
            ['name' => 'SUV', 'priority' => 2], 
            ['name' => 'Hatchback', 'priority' => 3],
        ];
        
        foreach ($basicCategories as $categoryData) {
            Category::factory()->active()->create($categoryData);
        }
        
        // Crear categorías adicionales aleatorias
        Category::factory()->premium()->count(3)->create();
        Category::factory()->basic()->count(5)->create();
        Category::factory()->count(10)->create();
    }
}
```

---

## 📊 Resultados de Testing

### ✅ Validaciones Realizadas

```
🔧 1. PROBANDO generación básica de CategoryFactory
✅ Categoría generada exitosamente:
   Nombre: Sedán
   Descripción: Vehículos tipo sedán de 4 puertas
   Prioridad: 10
   Descuento: 22.39%
   Estado: Activo
   Fecha Creación: 2025-03-04

🔧 2. PROBANDO estados específicos del Factory
✅ Categoría activa: Pickup (Estado: Activo)
✅ Categoría inactiva: Wagon (Estado: Inactivo)  
✅ Categoría premium: Minivan (Prioridad: 3, Descuento: 18.94%)
✅ Categoría básica: Pickup (Prioridad: 10, Descuento: 0.00%)
✅ Categoría personalizada: Categoría Personalizada

📊 RESUMEN DE PRUEBAS CATEGORYFACTORY
====================================
✅ Generación básica: Funcional
✅ Estados específicos: Funcional
✅ Generación múltiple: Funcional
✅ Tipos de datos: Correctos
✅ Campos fillable: Completos
✅ Datos realistas: Implementados
```

### 🔍 Validación de Tipos

- ✅ **name**: string con categorías realistas
- ✅ **description**: string|null con descripciones apropiadas
- ✅ **priority**: integer entre 1-10
- ✅ **discount_percentage**: float entre 0.00-25.00
- ✅ **estado**: boolean con 90% probabilidad activo
- ✅ **created_date**: date con fechas realistas

---

## 🎯 Características Avanzadas

### 🔧 Personalización de Datos

```php
// Crear categoría con datos específicos
$category = Category::factory()->create([
    'name' => 'Categoría Especial',
    'priority' => 1,
    'discount_percentage' => 20.00,
    'estado' => true
]);

// Usar callback para personalización compleja
$category = Category::factory()
    ->state(function (array $attributes) {
        return [
            'name' => 'Premium ' . $attributes['name'],
            'description' => 'Categoría premium: ' . $attributes['description'],
        ];
    })
    ->create();
```

### 🎨 Estados Encadenados

```php
// Combinar múltiples estados
$category = Category::factory()
    ->active()
    ->premium()
    ->withName('Super Premium')
    ->create();
```

### 📈 Generación Masiva

```php
// Para poblar BD de desarrollo
Category::factory()->count(50)->create();

// Para testing de performance
Category::factory()->count(1000)->create();
```

---

## 🏆 Beneficios del CategoryFactory

### 🚀 Para Desarrollo
- **Datos realistas** para probar la aplicación
- **Estados específicos** para diferentes escenarios
- **Generación rápida** de datos de prueba

### 🧪 Para Testing
- **Datos consistentes** en tests
- **Estados controlados** para validaciones específicas
- **Isolación de tests** con datos únicos

### 🔄 Para Seeders
- **Población fácil** de BD de desarrollo
- **Datos variados** para testing manual
- **Integración simple** con otros seeders

---

## 📋 Conclusión

✅ **PUNTO 7.1 COMPLETADO EXITOSAMENTE**

El **CategoryFactory** está completamente implementado con:

- 🎯 **Datos realistas** para categorías de vehículos
- 🎨 **Estados múltiples** para diferentes escenarios
- 🔧 **Personalización completa** de campos
- 🧪 **Testing exhaustivo** validado
- 📚 **Documentación completa** para uso

**Listo para uso en Seeders, Testing y desarrollo de la aplicación.**

---

*Documentación CategoryFactory - Laravel 11 Cars API*  
*Punto 7.1 - Factory implementado completamente*