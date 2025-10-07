<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Categorías realistas para vehículos
        $vehicleCategories = [
            ['name' => 'Sedán', 'desc' => 'Vehículos tipo sedán de 4 puertas'],
            ['name' => 'SUV', 'desc' => 'Vehículos utilitarios deportivos de gran tamaño'],
            ['name' => 'Hatchback', 'desc' => 'Vehículos compactos con portón trasero'],
            ['name' => 'Coupe', 'desc' => 'Vehículos deportivos de 2 puertas'],
            ['name' => 'Pickup', 'desc' => 'Camionetas con caja de carga'],
            ['name' => 'Convertible', 'desc' => 'Vehículos con techo retráctil'],
            ['name' => 'Wagon', 'desc' => 'Vehículos familiares con amplio espacio de carga'],
            ['name' => 'Crossover', 'desc' => 'Vehículos híbridos entre sedán y SUV'],
            ['name' => 'Minivan', 'desc' => 'Vehículos familiares de gran capacidad'],
            ['name' => 'Deportivo', 'desc' => 'Vehículos de alto rendimiento y velocidad'],
        ];

        $category = $this->faker->randomElement($vehicleCategories);

        return [
            // string(100) - Nombre de la categoría
            'name' => $category['name'],
            
            // text nullable - Descripción detallada
            'description' => $this->faker->boolean(85) ? $category['desc'] : null,
            
            // integer default(1) - Prioridad de la categoría (1-10)
            'priority' => $this->faker->numberBetween(1, 10),
            
            // decimal(5,2) default(0.00) - Porcentaje de descuento (0-25%)
            'discount_percentage' => $this->faker->randomFloat(2, 0, 25.00),
            
            // boolean default(true) - Estado activo/inactivo
            'estado' => $this->faker->boolean(90), // 90% probabilidad de estar activo
            
            // date default(now()) - Fecha de creación
            'created_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        ];
    }

    /**
     * Estado específico: Categoría activa
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => true,
        ]);
    }

    /**
     * Estado específico: Categoría inactiva
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => false,
        ]);
    }

    /**
     * Estado específico: Categoría premium con descuento alto
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $this->faker->numberBetween(1, 3),
            'discount_percentage' => $this->faker->randomFloat(2, 15.00, 25.00),
            'estado' => true,
        ]);
    }

    /**
     * Estado específico: Categoría básica sin descuento
     */
    public function basic(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $this->faker->numberBetween(7, 10),
            'discount_percentage' => 0.00,
            'description' => null,
        ]);
    }

    /**
     * Estado específico: Categoría con nombre personalizado
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }
}
