# Documentación de Relaciones Eloquent - Cars API

## 📖 Guía Completa de Relaciones entre Category y Car

Esta documentación describe todas las relaciones Eloquent implementadas siguiendo las mejores prácticas de Laravel.

---

## 🔗 Relaciones Básicas

### 1. One-to-Many (Category → Cars)

**Modelo Category:**
```php
/**
 * Relación One-to-Many: Una categoría tiene muchos carros
 */
public function cars()
{
    return $this->hasMany(Car::class, 'category_id', 'id');
}
```

**Ejemplo de uso:**
```php
// Obtener todos los carros de una categoría
$category = Category::find(1);
$cars = $category->cars;

// Con eager loading
$category = Category::with('cars')->find(1);
```

### 2. Many-to-One (Car → Category)

**Modelo Car:**
```php
/**
 * Relación Many-to-One: Un carro pertenece a una categoría
 */
public function category()
{
    return $this->belongsTo(Category::class, 'category_id', 'id');
}
```

**Ejemplo de uso:**
```php
// Obtener la categoría de un carro
$car = Car::find(1);
$category = $car->category;

// Con eager loading
$car = Car::with('category')->find(1);
```

---

## 🎯 Relaciones Específicas

### 1. Carros Activos por Categoría

```php
// En Category Model
public function activeCars()
{
    return $this->hasMany(Car::class, 'category_id', 'id')
                ->where('car_status', true);
}
```

**Ejemplo de uso:**
```php
$category = Category::find(1);
$activeCars = $category->activeCars;
echo "Carros activos: " . $activeCars->count();
```

### 2. Carros con Código de Barras por Categoría

```php
// En Category Model
public function carsWithBarcode()
{
    return $this->hasMany(Car::class, 'category_id', 'id')
                ->whereNotNull('codigo_barras');
}
```

**Ejemplo de uso:**
```php
$category = Category::find(1);
$carsWithBarcode = $category->carsWithBarcode;
foreach ($carsWithBarcode as $car) {
    echo "Código: " . $car->codigo_barras;
}
```

---

## 🔍 Query Scopes

### Scopes de Category

#### 1. Categorías Activas
```php
public function scopeActive($query)
{
    return $query->where('estado', true);
}
```

**Uso:**
```php
$activeCategories = Category::active()->get();
```

#### 2. Categorías con Carros
```php
public function scopeWithCars($query)
{
    return $query->has('cars');
}
```

**Uso:**
```php
$categoriesWithCars = Category::withCars()->get();
```

#### 3. Por Prioridad
```php
public function scopeByPriority($query)
{
    return $query->orderBy('priority', 'asc');
}
```

**Uso:**
```php
$categoriesByPriority = Category::byPriority()->get();
```

### Scopes de Car

#### 1. Carros Activos
```php
public function scopeActive($query)
{
    return $query->where('car_status', true);
}
```

**Uso:**
```php
$activeCars = Car::active()->get();
```

#### 2. Con Categoría Activa
```php
public function scopeWithActiveCategory($query)
{
    return $query->whereHas('category', function ($query) {
        $query->where('estado', true);
    });
}
```

**Uso:**
```php
$carsWithActiveCategory = Car::withActiveCategory()->get();
```

#### 3. Con Código de Barras
```php
public function scopeWithBarcode($query)
{
    return $query->whereNotNull('codigo_barras');
}
```

**Uso:**
```php
$carsWithBarcode = Car::withBarcode()->get();
```

#### 4. Por Rango de Años
```php
public function scopeByYearRange($query, $from, $to)
{
    return $query->whereBetween('car_year', [$from, $to]);
}
```

**Uso:**
```php
$recentCars = Car::byYearRange(2020, 2024)->get();
```

#### 5. Por Rango de Precios
```php
public function scopeByPriceRange($query, $min, $max)
{
    return $query->whereBetween('car_price', [$min, $max]);
}
```

**Uso:**
```php
$affordableCars = Car::byPriceRange(20000, 50000)->get();
```

---

## ✨ Accessors

### Accessors de Category

#### 1. Conteo de Carros
```php
public function getCarsCountAttribute()
{
    return $this->cars()->count();
}
```

**Uso:**
```php
$category = Category::find(1);
echo "Total carros: " . $category->cars_count;
```

#### 2. Nombre Formateado
```php
public function getFormattedNameAttribute()
{
    return ucwords(strtolower($this->name));
}
```

**Uso:**
```php
$category = Category::find(1);
echo $category->formatted_name;
```

#### 3. Estado Activo
```php
public function getIsActiveAttribute()
{
    return $this->estado === true;
}
```

**Uso:**
```php
$category = Category::find(1);
if ($category->is_active) {
    echo "Categoría activa";
}
```

### Accessors de Car

#### 1. Nombre Completo
```php
public function getFullNameAttribute()
{
    return "{$this->car_make} {$this->car_model} ({$this->car_year})";
}
```

**Uso:**
```php
$car = Car::find(1);
echo $car->full_name; // "Toyota Corolla (2023)"
```

#### 2. Precio Formateado
```php
public function getFormattedPriceAttribute()
{
    return '$' . number_format($this->car_price, 2);
}
```

**Uso:**
```php
$car = Car::find(1);
echo $car->formatted_price; // "$25,000.00"
```

#### 3. Edad del Carro
```php
public function getAgeAttribute()
{
    return date('Y') - $this->car_year;
}
```

**Uso:**
```php
$car = Car::find(1);
echo "Edad: " . $car->age . " años";
```

---

## ⚡ Ejemplos Avanzados

### 1. Eager Loading Optimizado

```php
// Cargar categorías con sus carros activos
$categories = Category::with(['activeCars' => function($query) {
    $query->withBarcode()
          ->orderBy('car_year', 'desc')
          ->limit(5);
}])->get();

foreach ($categories as $category) {
    echo "Categoría: {$category->name}\n";
    foreach ($category->activeCars as $car) {
        echo "  - {$car->full_name} - {$car->codigo_barras}\n";
    }
}
```

### 2. Consultas Complejas

```php
// Categorías activas con carros costosos recientes
$expensiveRecentCars = Category::active()
    ->withCars()
    ->with(['cars' => function($query) {
        $query->byYearRange(2020, 2024)
              ->byPriceRange(50000, 200000)
              ->withBarcode()
              ->orderBy('car_price', 'desc');
    }])
    ->byPriority()
    ->get();
```

### 3. Agregaciones

```php
// Estadísticas por categoría
$categoryStats = Category::withCount([
    'cars',
    'activeCars',
    'carsWithBarcode'
])->get();

foreach ($categoryStats as $category) {
    echo "{$category->name}:\n";
    echo "  Total: {$category->cars_count}\n";
    echo "  Activos: {$category->active_cars_count}\n";
    echo "  Con código: {$category->cars_with_barcode_count}\n";
}
```

### 4. Filtros Combinados

```php
// Carros activos de categorías activas con código de barras
$filteredCars = Car::active()
    ->withActiveCategory()
    ->withBarcode()
    ->byYearRange(2018, 2024)
    ->with('category')
    ->orderBy('car_price', 'desc')
    ->paginate(10);
```

---

## 📊 Casos de Uso Comunes

### 1. Dashboard de Categorías
```php
$dashboardData = Category::active()
    ->withCount(['cars', 'activeCars'])
    ->with(['cars' => function($query) {
        $query->select('id_car', 'car_make', 'car_model', 'car_price', 'category_id')
              ->orderBy('car_price', 'desc')
              ->limit(3);
    }])
    ->byPriority()
    ->get();
```

### 2. Búsqueda de Carros
```php
public function searchCars($filters = [])
{
    $query = Car::with('category');
    
    if (!empty($filters['category_id'])) {
        $query->where('category_id', $filters['category_id']);
    }
    
    if (!empty($filters['year_from'])) {
        $query->where('car_year', '>=', $filters['year_from']);
    }
    
    if (!empty($filters['price_max'])) {
        $query->where('car_price', '<=', $filters['price_max']);
    }
    
    if (!empty($filters['with_barcode'])) {
        $query->withBarcode();
    }
    
    return $query->active()->paginate(20);
}
```

### 3. Reporte de Inventario
```php
$inventoryReport = Category::active()
    ->with(['cars' => function($query) {
        $query->select('id_car', 'car_make', 'car_model', 'car_year', 
                      'car_price', 'car_status', 'codigo_barras', 'category_id');
    }])
    ->get()
    ->map(function($category) {
        return [
            'category' => $category->formatted_name,
            'total_cars' => $category->cars->count(),
            'active_cars' => $category->cars->where('car_status', true)->count(),
            'average_price' => $category->cars->avg('car_price'),
            'cars_with_barcode' => $category->cars->whereNotNull('codigo_barras')->count(),
        ];
    });
```

---

## ✅ Verificación de Implementación

Todas las relaciones han sido probadas exitosamente:

- ✅ **Relaciones básicas**: hasMany y belongsTo funcionando
- ✅ **Relaciones específicas**: activeCars y carsWithBarcode operativas  
- ✅ **Query Scopes**: 8 scopes implementados y probados
- ✅ **Accessors**: 7 accessors funcionando correctamente
- ✅ **Eager Loading**: Optimización de consultas verificada
- ✅ **Consultas complejas**: Combinaciones avanzadas ejecutándose

La implementación sigue completamente la documentación oficial de Laravel Eloquent y las mejores prácticas recomendadas.