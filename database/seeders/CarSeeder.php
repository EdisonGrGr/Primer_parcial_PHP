<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Category;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder utiliza CarFactory para poblar la tabla de cars.
     * IMPORTANTE: Requiere que CategorySeeder se ejecute primero
     * para asegurar que existan categorías para asignar FK.
     */
    public function run(): void
    {
        // Verificar que existan categorías antes de crear carros
        $categoriesCount = Category::count();
        
        if ($categoriesCount === 0) {
            $this->command->warn('⚠️  No se encontraron categorías. Ejecutando CategorySeeder primero...');
            $this->call(CategorySeeder::class);
        }

        // 1. Crear carros de lujo disponibles
        Car::factory()
            ->luxury()
            ->available()
            ->count(8)
            ->create();

        // 2. Crear carros económicos
        Car::factory()
            ->economy()
            ->available()
            ->count(12)
            ->create();

        // 3. Crear carros no disponibles (para testing)
        Car::factory()
            ->unavailable()
            ->count(5)
            ->create();

        // 4. Crear algunos carros sin categoría
        Car::factory()
            ->withoutCategory()
            ->available()
            ->count(3)
            ->create();

        // 5. Crear carros con categorías específicas si existen
        $sedanCategory = Category::where('name', 'Sedán')->first();
        if ($sedanCategory) {
            Car::factory()
                ->withCategory($sedanCategory->id)
                ->available()
                ->count(6)
                ->create();
        }

        $suvCategory = Category::where('name', 'SUV')->first();
        if ($suvCategory) {
            Car::factory()
                ->withCategory($suvCategory->id)
                ->luxury()
                ->count(4)
                ->create();
        }

        // 6. Crear carros completamente aleatorios
        Car::factory()
            ->count(15)
            ->create();

        // Mostrar resumen de lo creado
        $totalExpected = 8 + 12 + 5 + 3 + 6 + 4 + 15;
        
        $this->command->info('CarSeeder completado:');
        $this->command->info('- Carros de lujo disponibles: 8');
        $this->command->info('- Carros económicos: 12');
        $this->command->info('- Carros no disponibles: 5');
        $this->command->info('- Carros sin categoría: 3');
        $this->command->info('- Carros Sedán: 6');
        $this->command->info('- Carros SUV lujo: 4');
        $this->command->info('- Carros aleatorios: 15');
        $this->command->info('📊 Total esperado: ' . $totalExpected . ' carros');
        
        // Mostrar estadísticas de categorías asignadas
        $carsWithCategory = Car::whereNotNull('category_id')->count();
        $carsWithoutCategory = Car::whereNull('category_id')->count();
        
        $this->command->info('📈 Estadísticas:');
        $this->command->info('  - Carros con categoría: ' . $carsWithCategory);
        $this->command->info('  - Carros sin categoría: ' . $carsWithoutCategory);
    }
}
