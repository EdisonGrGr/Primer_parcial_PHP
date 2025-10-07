<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * ORDEN IMPORTANTE: Categories debe ejecutarse ANTES que Cars
     * debido a la dependencia de clave foránea (category_id en cars).
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando Database Seeding...');
        
        // 1. PRIMERO: Seedear categorías (requerido para FK en cars)
        $this->command->info('📂 Ejecutando CategorySeeder...');
        $this->call(CategorySeeder::class);
        
        // 2. SEGUNDO: Seedear carros (depende de categories)
        $this->command->info('🚗 Ejecutando CarSeeder...');
        $this->call(CarSeeder::class);
        
        // 3. OPCIONAL: Crear usuario de prueba
        $this->command->info('👤 Creando usuario de prueba...');
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->command->info('✅ Database Seeding completado exitosamente!');
        
        // Mostrar estadísticas finales
        $this->showFinalStats();
    }
    
    /**
     * Mostrar estadísticas finales del seeding
     */
    private function showFinalStats(): void
    {
        $categoriesCount = \App\Models\Category::count();
        $carsCount = \App\Models\Car::count();
        $usersCount = \App\Models\User::count();
        
        $this->command->info('📊 ESTADÍSTICAS FINALES:');
        $this->command->info("   📂 Categorías: {$categoriesCount}");
        $this->command->info("   🚗 Carros: {$carsCount}");
        $this->command->info("   👤 Usuarios: {$usersCount}");
        
        // Estadísticas de relaciones
        $carsWithCategory = \App\Models\Car::whereNotNull('category_id')->count();
        $carsWithoutCategory = \App\Models\Car::whereNull('category_id')->count();
        
        $this->command->info('🔗 RELACIONES:');
        $this->command->info("   📎 Carros con categoría: {$carsWithCategory}");
        $this->command->info("   🔓 Carros sin categoría: {$carsWithoutCategory}");
        
        // Top categorías con más carros
        $topCategories = \App\Models\Category::withCount('cars')
            ->orderBy('cars_count', 'desc')
            ->take(3)
            ->get();
            
        $this->command->info('🏆 TOP CATEGORÍAS:');
        foreach ($topCategories as $category) {
            $this->command->info("   {$category->name}: {$category->cars_count} carros");
        }
    }
}
