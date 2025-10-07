<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
   
    public function run(): void
    {
        
        $essentialCategories = [
            [
                'name' => 'Sedán',
                'description' => 'Vehículos tipo sedán de 4 puertas, elegantes y cómodos para uso diario',
                'priority' => 1,
                'discount_percentage' => 5.50,
                'estado' => true,
            ],
            [
                'name' => 'SUV',
                'description' => 'Vehículos utilitarios deportivos, ideales para familias y aventuras',
                'priority' => 2,
                'discount_percentage' => 3.25,
                'estado' => true,
            ],
            [
                'name' => 'Deportivo',
                'description' => 'Vehículos de alto rendimiento, velocidad y diseño aerodinámico',
                'priority' => 3,
                'discount_percentage' => 0.00,
                'estado' => false, 
            ],
            [
                'name' => 'Pickup',
                'description' => 'Camionetas para trabajo, transporte de carga y uso comercial',
                'priority' => 4,
                'discount_percentage' => 7.75,
                'estado' => true,
            ],
            [
                'name' => 'Hatchback',
                'description' => 'Vehículos compactos y versátiles para ciudad',
                'priority' => 5,
                'discount_percentage' => 4.00,
                'estado' => true,
            ],
        ];

        
        foreach ($essentialCategories as $categoryData) {
            Category::factory()->create($categoryData);
        }

        
        Category::factory()
            ->premium()
            ->count(3)
            ->create();

        
        Category::factory()
            ->basic()
            ->count(4)
            ->create();

        Category::factory()
            ->active()
            ->count(8)
            ->create();

        Category::factory()
            ->inactive()
            ->count(2)
            ->create();

        Category::factory()
            ->count(5)
            ->create();

        
        $this->command->info('CategorySeeder completado:');
        $this->command->info('- Categorías esenciales: ' . count($essentialCategories));
        $this->command->info('- Categorías premium: 3');
        $this->command->info('- Categorías básicas: 4');
        $this->command->info('- Categorías activas aleatorias: 8');
        $this->command->info('- Categorías inactivas: 2');
        $this->command->info('- Categorías aleatorias: 5');
        $this->command->info('📊 Total esperado: ' . (count($essentialCategories) + 3 + 4 + 8 + 2 + 5) . ' categorías');
    }
}
