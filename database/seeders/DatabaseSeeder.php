<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $this->command->info('🚀 Iniciando Database Seeding...');
        
        $this->command->info('📂 Ejecutando CategorySeeder...');
        $this->call(CategorySeeder::class);
        
        $this->command->info('🚗 Ejecutando CarSeeder...');
        $this->call(CarSeeder::class);
        
        $this->command->info('👤 Creando usuario administrador...');
        User::factory()->create([
            'name' => 'Jhon Garcia',
            'email' => 'jhon.garcia@ucaldas.edu.co', //Usuario: jhon.garcia@ucaldas.edu.co
            'password' => bcrypt('password'), // Password: password
        ]);

        $this->command->info('✅ Database Seeding completado exitosamente!');
        
        $this->showFinalStats();
    }
    
    
    private function showFinalStats(): void
    {
        $categoriesCount = \App\Models\Category::count();
        $carsCount = \App\Models\Car::count();
        $usersCount = \App\Models\User::count();
        
        $this->command->info('📊 ESTADÍSTICAS FINALES:');
        $this->command->info("   📂 Categorías: {$categoriesCount}");
        $this->command->info("   🚗 Carros: {$carsCount}");
        $this->command->info("   👤 Usuarios: {$usersCount}");
        
        
        $carsWithCategory = \App\Models\Car::whereNotNull('category_id')->count();
        $carsWithoutCategory = \App\Models\Car::whereNull('category_id')->count();
        
        $this->command->info('🔗 RELACIONES:');
        $this->command->info("   📎 Carros con categoría: {$carsWithCategory}");
        $this->command->info("   🔓 Carros sin categoría: {$carsWithoutCategory}");
        
        
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
