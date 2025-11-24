<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        
        $makes = [
            'Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan', 'BMW', 'Mercedes-Benz',
            'Audi', 'Volkswagen', 'Hyundai', 'Kia', 'Mazda', 'Subaru', 'Lexus',
            'Infiniti', 'Acura', 'Cadillac', 'Lincoln', 'Buick', 'GMC', 'Jeep',
            'Dodge', 'Chrysler', 'Ram', 'Mitsubishi', 'Volvo', 'Jaguar', 'Land Rover',
            'Porsche', 'Ferrari', 'Lamborghini', 'Maserati', 'Bentley', 'Rolls-Royce'
        ];

        
        $models = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Highlander', 'Prius', 'Tacoma', 'Sienna', 'Yaris', 'Avalon'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey', 'Fit', 'HR-V', 'Ridgeline', 'Passport'],
            'Ford' => ['F-150', 'Mustang', 'Explorer', 'Escape', 'Fusion', 'Focus', 'Expedition', 'Edge', 'Ranger'],
            'Chevrolet' => ['Silverado', 'Camaro', 'Equinox', 'Malibu', 'Tahoe', 'Suburban', 'Corvette', 'Cruze', 'Traverse'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', 'X1', '7 Series', 'Z4', 'X7', 'i4'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE', 'A-Class', 'CLA', 'GLS', 'AMG GT'],
            'Audi' => ['A3', 'A4', 'A6', 'Q3', 'Q5', 'Q7', 'A8', 'TT', 'e-tron'],
            'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Pathfinder', 'Maxima', '370Z', 'Titan', 'Murano', 'Leaf'],
            'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Genesis', 'Kona', 'Palisade', 'Veloster'],
            'Kia' => ['Optima', 'Forte', 'Sorento', 'Sportage', 'Stinger', 'Soul', 'Telluride', 'Rio'],
            'Mazda' => ['Mazda3', 'Mazda6', 'CX-5', 'CX-9', 'MX-5 Miata', 'CX-3', 'CX-30'],
            'Subaru' => ['Outback', 'Forester', 'Impreza', 'Legacy', 'Crosstrek', 'Ascent', 'WRX'],
            'Volkswagen' => ['Jetta', 'Passat', 'Tiguan', 'Atlas', 'Golf', 'Beetle', 'Arteon'],
            'Jeep' => ['Wrangler', 'Grand Cherokee', 'Cherokee', 'Compass', 'Renegade', 'Gladiator'],
            'Dodge' => ['Charger', 'Challenger', 'Durango', 'Journey', 'Grand Caravan'],
            'Ferrari' => ['488', 'F8', 'Portofino', 'Roma', 'SF90', '812', 'LaFerrari'],
            'Lamborghini' => ['Huracán', 'Aventador', 'Urus', 'Gallardo', 'Murciélago'],
            'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan', 'Boxster'],
        ];

        $selectedMake = $this->faker->randomElement($makes);
        
        
        if (isset($models[$selectedMake])) {
            $selectedModel = $this->faker->randomElement($models[$selectedMake]);
        } else {
            $modelPrefixes = ['Series', 'Class', 'Model', 'Type'];
            $selectedModel = $this->faker->randomElement($modelPrefixes) . ' ' . $this->faker->randomElement(['A', 'B', 'C', 'X', 'Z']) . $this->faker->numberBetween(1, 9);
        }

        return [
            'car_make'  => $selectedMake,
            'car_model' => $selectedModel,
            'car_year'  => $this->faker->numberBetween(2000, date('Y')),
            'car_price' => $this->faker->randomFloat(2, 15000, 150000),
            'color' => $this->faker->safeColorName(),
            'car_status'=> $this->faker->boolean(85), 
            
            'category_id' => function () {
                $categoryIds = \App\Models\Category::pluck('id')->toArray();
                if (empty($categoryIds)) {
                    return null;
                }
                
                
                return $this->faker->randomElement($categoryIds);
            },
            
            
            'codigo_barras' => function () {
                $prefix = $this->faker->randomElement(['CAR', 'VEH', 'AUTO', 'MOT']);
                $year = date('Y');
                $randomNumber = $this->faker->unique()->numberBetween(100000, 999999);
                return $prefix . $year . '_' . $randomNumber;
            },
        ];
    }

    
    public function withCategory($categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }

    
    public function withoutCategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => null,
        ]);
    }

    
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'car_status' => true,
        ]);
    }

    
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'car_status' => false,
        ]);
    }

    
    public function luxury(): static
    {
        return $this->state(fn (array $attributes) => [
            'car_price' => $this->faker->randomFloat(2, 80000, 200000),
            'car_year' => $this->faker->numberBetween(2020, date('Y')),
        ]);
    }

    
    public function economy(): static
    {
        return $this->state(fn (array $attributes) => [
            'car_price' => $this->faker->randomFloat(2, 10000, 25000),
            'car_year' => $this->faker->numberBetween(2010, 2018),
        ]);
    }

    
    public function withBarcode(string $barcode): static
    {
        return $this->state(fn (array $attributes) => [
            'codigo_barras' => $barcode,
        ]);
    }
}
