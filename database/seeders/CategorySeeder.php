<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder utiliza CategoryFactory para poblar la tabla de categorías
     * con una combinación de datos fijos (esenciales) y datos aleatorios.
     */
    public function run(): void
    {
        // 1. Crear categorías básicas esenciales del sistema
        // Estas son categorías core que siempre deben existir
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
                'estado' => false, // Inactivo para testing
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

        // Crear categorías esenciales usando Factory con datos específicos
        foreach ($essentialCategories as $categoryData) {
            Category::factory()->create($categoryData);
        }

        // 2. Crear categorías premium usando Factory con estado específico
        Category::factory()
            ->premium()
            ->count(3)
            ->create();

        // 3. Crear categorías básicas usando Factory
        Category::factory()
            ->basic()
            ->count(4)
            ->create();

        // 4. Crear categorías aleatorias activas
        Category::factory()
            ->active()
            ->count(8)
            ->create();

        // 5. Crear algunas categorías inactivas para testing
        Category::factory()
            ->inactive()
            ->count(2)
            ->create();

        // 6. Crear categorías completamente aleatorias
        Category::factory()
            ->count(5)
            ->create();

        // Mostrar resumen de lo creado
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
