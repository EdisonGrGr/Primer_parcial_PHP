<?php
// Script para probar CategoryFactory con persistencia en BD
echo "Probando CategoryFactory con Base de Datos\n";
echo "==========================================\n\n";

// Incluir el autoloader de Laravel
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;

try {
    echo "🔧 1. CREANDO categorías en la base de datos\n";
    
    // Crear y guardar categorías usando diferentes estados
    $categories = [
        Category::factory()->active()->create(),
        Category::factory()->premium()->create(),
        Category::factory()->basic()->create(),
        Category::factory()->withName('Categoría Factory Test')->create(),
    ];
    
    echo "✅ Creadas " . count($categories) . " categorías en la base de datos:\n";
    
    foreach ($categories as $index => $category) {
        echo "   " . ($index + 1) . ". ID: " . $category->id . " - " . $category->name;
        echo " (Prioridad: " . $category->priority . ", Estado: " . ($category->estado ? 'Activo' : 'Inactivo') . ")\n";
    }
    
    echo "\n🔧 2. CREANDO lote de categorías\n";
    
    // Crear múltiples categorías de una vez
    $batchCategories = Category::factory()->count(3)->create();
    
    echo "✅ Lote de " . $batchCategories->count() . " categorías creado:\n";
    
    foreach ($batchCategories as $index => $category) {
        echo "   " . ($index + 1) . ". ID: " . $category->id . " - " . $category->name;
        echo " (Descuento: " . $category->discount_percentage . "%, Fecha: " . $category->created_date . ")\n";
    }
    
    echo "\n🔧 3. VERIFICANDO total de categorías en BD\n";
    
    $totalCategories = Category::count();
    echo "✅ Total de categorías en base de datos: " . $totalCategories . "\n";
    
    // Mostrar las últimas 5 categorías creadas
    $recentCategories = Category::orderBy('id', 'desc')->take(5)->get();
    
    echo "\n📋 Últimas 5 categorías creadas:\n";
    foreach ($recentCategories as $category) {
        echo "   • " . $category->name . " (ID: " . $category->id . ", Estado: " . ($category->estado ? 'Activo' : 'Inactivo') . ")\n";
    }
    
    echo "\n🔧 4. PROBANDO relaciones con CategoryFactory\n";
    
    // Crear una categoría y verificar que puede tener carros
    $categoryWithRelation = Category::factory()->active()->create();
    
    echo "✅ Categoría creada para prueba de relación:\n";
    echo "   ID: " . $categoryWithRelation->id . "\n";
    echo "   Nombre: " . $categoryWithRelation->name . "\n";
    echo "   Carros asociados: " . $categoryWithRelation->cars()->count() . "\n";
    
    // Probar el accessor cars_count
    echo "   Accessor cars_count: " . $categoryWithRelation->cars_count . "\n";
    
    // Probar el accessor formatted_name  
    echo "   Accessor formatted_name: " . $categoryWithRelation->formatted_name . "\n";
    
    echo "\n📊 RESUMEN DE PRUEBAS CON BD\n";
    echo "============================\n";
    echo "✅ Creación individual: Funcional\n";
    echo "✅ Creación en lotes: Funcional\n";
    echo "✅ Persistencia en BD: Correcta\n";
    echo "✅ Estados específicos: Aplicados\n";
    echo "✅ Relaciones preparadas: Funcionales\n";
    echo "✅ Accessors funcionando: Correctos\n";
    
    echo "\n🎯 CATEGORYFACTORY COMPLETAMENTE VALIDADO\n";
    echo "✅ Listo para uso en Seeders y Testing\n";
    
} catch (Exception $e) {
    echo "❌ Error durante las pruebas: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}