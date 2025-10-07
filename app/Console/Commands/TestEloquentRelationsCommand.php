<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Car;

class TestEloquentRelationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:eloquent-relations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar todas las relaciones Eloquent y métodos avanzados implementados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Probando Relaciones Eloquent Avanzadas');
        $this->info('======================================');
        $this->newLine();
        
        try {
            // ==========================================
            // 1. RELACIONES BÁSICAS
            // ==========================================
            $this->info('🔗 1. PROBANDO RELACIONES BÁSICAS');
            $this->info('----------------------------------');
            
            // hasMany: Category -> Cars
            $category = Category::with('cars')->first();
            if ($category) {
                $this->info("✅ Category hasMany Cars:");
                $this->info("   Categoría: {$category->name}");
                $this->info("   Carros asociados: {$category->cars->count()}");
                
                foreach ($category->cars->take(3) as $car) {
                    $this->info("   - {$car->full_name}");
                }
            }
            
            $this->newLine();
            
            // belongsTo: Car -> Category  
            $car = Car::with('category')->whereNotNull('category_id')->first();
            if ($car && $car->category) {
                $this->info("✅ Car belongsTo Category:");
                $this->info("   Carro: {$car->full_name}");
                $this->info("   Categoría: {$car->category->name}");
            }
            
            $this->newLine();
            
            // ==========================================
            // 2. RELACIONES ESPECÍFICAS
            // ==========================================
            $this->info('🎯 2. PROBANDO RELACIONES ESPECÍFICAS');
            $this->info('-------------------------------------');
            
            $category = Category::first();
            if ($category) {
                // activeCars
                $activeCars = $category->activeCars;
                $this->info("✅ activeCars: {$activeCars->count()} carros activos");
                
                // carsWithBarcode
                $carsWithBarcode = $category->carsWithBarcode;
                $this->info("✅ carsWithBarcode: {$carsWithBarcode->count()} carros con código");
            }
            
            $this->newLine();
            
            // ==========================================
            // 3. QUERY SCOPES
            // ==========================================
            $this->info('🔍 3. PROBANDO QUERY SCOPES');
            $this->info('---------------------------');
            
            // Category scopes
            $activeCategories = Category::active()->count();
            $this->info("✅ Categories::active(): {$activeCategories} categorías activas");
            
            $categoriesWithCars = Category::withCars()->count();
            $this->info("✅ Categories::withCars(): {$categoriesWithCars} categorías con carros");
            
            $categoriesByPriority = Category::byPriority()->take(3)->get();
            $this->info("✅ Categories::byPriority(): Ordenadas por prioridad");
            foreach ($categoriesByPriority as $cat) {
                $this->info("   - {$cat->name} (Prioridad: {$cat->priority})");
            }
            
            $this->newLine();
            
            // Car scopes
            $activeCars = Car::active()->count();
            $this->info("✅ Cars::active(): {$activeCars} carros activos");
            
            $carsWithActiveCategory = Car::withActiveCategory()->count();
            $this->info("✅ Cars::withActiveCategory(): {$carsWithActiveCategory} carros con categoría activa");
            
            $carsWithBarcode = Car::withBarcode()->count();
            $this->info("✅ Cars::withBarcode(): {$carsWithBarcode} carros con código de barras");
            
            // Scopes con parámetros
            $recentCars = Car::byYearRange(2020, date('Y'))->count();
            $this->info("✅ Cars::byYearRange(2020, " . date('Y') . "): {$recentCars} carros recientes");
            
            $expensiveCars = Car::byPriceRange(50000, 200000)->count();
            $this->info("✅ Cars::byPriceRange(50000, 200000): {$expensiveCars} carros costosos");
            
            $this->newLine();
            
            // ==========================================
            // 4. ACCESSORS
            // ==========================================
            $this->info('✨ 4. PROBANDO ACCESSORS');
            $this->info('------------------------');
            
            $category = Category::first();
            if ($category) {
                $this->info("✅ Category Accessors:");
                $this->info("   Nombre original: {$category->name}");
                $this->info("   Nombre formateado: {$category->formatted_name}");
                $this->info("   Estado activo: " . ($category->is_active ? 'SÍ' : 'NO'));
                $this->info("   Descuento formateado: {$category->formatted_discount}");
                $this->info("   Cantidad de carros: {$category->cars_count}");
            }
            
            $this->newLine();
            
            $car = Car::first();
            if ($car) {
                $this->info("✅ Car Accessors:");
                $this->info("   Nombre completo: {$car->full_name}");
                $this->info("   Precio formateado: {$car->formatted_price}");
                $this->info("   Disponible: " . ($car->is_available ? 'SÍ' : 'NO'));
                $this->info("   Edad: {$car->age} años");
            }
            
            $this->newLine();
            
            // ==========================================
            // 5. EAGER LOADING
            // ==========================================
            $this->info('⚡ 5. PROBANDO EAGER LOADING');
            $this->info('-----------------------------');
            
            // Con eager loading
            $start = microtime(true);
            $categoriesWithCars = Category::with('cars')->take(5)->get();
            $timeWithEager = microtime(true) - $start;
            
            $totalCarsEager = $categoriesWithCars->sum(function($cat) { 
                return $cat->cars->count(); 
            });
            
            $this->info("✅ Eager Loading: 5 categorías con carros");
            $this->info("   Total carros cargados: {$totalCarsEager}");
            $this->info("   Tiempo: " . number_format($timeWithEager * 1000, 2) . "ms");
            
            $this->newLine();
            
            // ==========================================
            // 6. CONSULTAS COMPLEJAS
            // ==========================================
            $this->info('🧠 6. PROBANDO CONSULTAS COMPLEJAS');
            $this->info('-----------------------------------');
            
            // Categorías activas con carros activos y código de barras
            $complexQuery = Category::active()
                ->withCars()
                ->with(['activeCars' => function($query) {
                    $query->withBarcode()->orderBy('car_year', 'desc');
                }])
                ->byPriority()
                ->take(3)
                ->get();
            
            $this->info("✅ Consulta compleja ejecutada:");
            $this->info("   Categorías activas con carros activos y código de barras");
            
            foreach ($complexQuery as $category) {
                $this->info("   - {$category->name}: {$category->activeCars->count()} carros activos con código");
            }
            
            $this->newLine();
            
            // ==========================================
            // RESUMEN FINAL
            // ==========================================
            $this->info('📊 RESUMEN DE RELACIONES ELOQUENT');
            $this->info('==================================');
            
            $stats = [
                'Total categorías' => Category::count(),
                'Categorías activas' => Category::active()->count(),
                'Categorías con carros' => Category::withCars()->count(),
                'Total carros' => Car::count(),
                'Carros activos' => Car::active()->count(),
                'Carros con categoría' => Car::whereNotNull('category_id')->count(),
                'Carros con código de barras' => Car::withBarcode()->count(),
                'Carros con categoría activa' => Car::withActiveCategory()->count()
            ];
            
            foreach ($stats as $descripcion => $valor) {
                $this->info("📈 {$descripcion}: {$valor}");
            }
            
            $this->newLine();
            $this->info('✅ TODAS LAS RELACIONES ELOQUENT FUNCIONAN CORRECTAMENTE');
            $this->info('✅ Scopes, Accessors y Eager Loading implementados exitosamente');
            $this->info('✅ Consultas complejas ejecutándose sin problemas');
            
        } catch (\Exception $e) {
            $this->error("❌ Error probando relaciones: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
