<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Car;

class TestRelationshipCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:relationship';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the 1:N relationship between Categories and Cars';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Category-Car 1:N Relationship');
        $this->info('====================================');
        $this->newLine();
        
        // First, let's check what data we have
        $this->info('🔍 Checking existing data:');
        $cars = Car::limit(3)->get();
        foreach ($cars as $car) {
            $this->info("Car ID: {$car->id_car}, Make: " . ($car->car_make ?? 'NULL') . ", Model: " . ($car->car_model ?? 'NULL') . ", Category ID: " . ($car->category_id ?? 'NULL'));
        }
        $this->newLine();
        
        try {
            // Create a test category
            $category = Category::create([
                'name' => 'Sedanes Ejecutivos',
                'description' => 'Categoría para carros sedanes ejecutivos de alta gama',
                'priority' => 1,
                'discount_percentage' => 15.75,
                'estado' => true,
                'created_date' => now()->toDateString()
            ]);
            
            $this->info("✅ Created test category with ID: {$category->id}");
            
            // Check existing cars
            $carsCount = Car::count();
            $this->info("📊 Total cars in database: {$carsCount}");
            
            if ($carsCount > 0) {
                // Update existing cars to belong to our category
                $cars = Car::limit(2)->get();
                foreach ($cars as $car) {
                    $car->update(['category_id' => $category->id]);
                    $this->info("✅ Updated car '{$car->car_make} {$car->car_model}' to belong to category '{$category->name}'");
                }
                
                // Test hasMany relationship (Category -> Cars)
                $this->newLine();
                $this->info('🔍 Testing hasMany relationship (Category -> Cars):');
                $categoryWithCars = Category::with('cars')->find($category->id);
                $this->info("Category: {$categoryWithCars->name}");
                $this->info("Associated cars: {$categoryWithCars->cars->count()}");
                
                foreach ($categoryWithCars->cars as $car) {
                    $this->info("  - {$car->car_make} {$car->car_model} ({$car->car_year})");
                }
                
                // Test belongsTo relationship (Car -> Category)
                $this->newLine();
                $this->info('🔍 Testing belongsTo relationship (Car -> Category):');
                foreach ($cars as $car) {
                    $carWithCategory = Car::with('category')->find($car->id_car);
                    if ($carWithCategory && $carWithCategory->category) {
                        $this->info("Car: {$carWithCategory->car_make} {$carWithCategory->car_model} belongs to category: {$carWithCategory->category->name}");
                    }
                }
                
            } else {
                // Create test cars if none exist
                $testCar1 = Car::create([
                    'car_make' => 'BMW',
                    'car_model' => '530i',
                    'car_year' => 2024,
                    'car_price' => 45000.00,
                    'car_status' => true,
                    'category_id' => $category->id
                ]);
                
                $testCar2 = Car::create([
                    'car_make' => 'Mercedes-Benz',
                    'car_model' => 'E300',
                    'car_year' => 2024,
                    'car_price' => 52000.00,
                    'car_status' => true,
                    'category_id' => $category->id
                ]);
                
                $this->info("✅ Created test cars and assigned to category");
                
                // Test relationships with new cars
                $categoryWithCars = Category::with('cars')->find($category->id);
                $this->newLine();
                $this->info('🔍 Testing hasMany relationship (Category -> Cars):');
                $this->info("Category: {$categoryWithCars->name}");
                $this->info("Associated cars: {$categoryWithCars->cars->count()}");
                
                foreach ($categoryWithCars->cars as $car) {
                    $this->info("  - {$car->car_make} {$car->car_model} ({$car->car_year})");
                }
            }
            
            $this->newLine();
            $this->info('✅ 1:N Relationship test completed successfully!');
            $this->info('Both hasMany (Category->Cars) and belongsTo (Car->Category) relationships are working.');
            
        } catch (\Exception $e) {
            $this->error("❌ Error testing relationship: " . $e->getMessage());
        }
    }
}
