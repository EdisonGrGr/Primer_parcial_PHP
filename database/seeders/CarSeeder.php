<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Category;

class CarSeeder extends Seeder
{
    
    public function run(): void
    {
        $categoriesCount = Category::count();
        
        if ($categoriesCount === 0) {
            $this->command->warn('⚠️  No se encontraron categorías. Ejecutando CategorySeeder primero...');
            $this->call(CategorySeeder::class);
        }

        
        Car::factory()
            ->luxury()
            ->available()
            ->count(8)
            ->create();

        
        Car::factory()
            ->economy()
            ->available()
            ->count(12)
            ->create();

        
        Car::factory()
            ->unavailable()
            ->count(5)
            ->create();

        
        Car::factory()
            ->withoutCategory()
            ->available()
            ->count(3)
            ->create();

        
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

        
        Car::factory()
            ->count(15)
            ->create();

       
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
        
        
        $carsWithCategory = Car::whereNotNull('category_id')->count();
        $carsWithoutCategory = Car::whereNull('category_id')->count();
        
        $this->command->info('📈 Estadísticas:');
        $this->command->info('  - Carros con categoría: ' . $carsWithCategory);
        $this->command->info('  - Carros sin categoría: ' . $carsWithoutCategory);
    }
}
