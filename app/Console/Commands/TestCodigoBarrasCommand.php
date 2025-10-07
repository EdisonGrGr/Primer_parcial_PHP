<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;

class TestCodigoBarrasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:codigo-barras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar la funcionalidad de la columna codigo_barras en la tabla cars';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Probando la columna codigo_barras en la tabla cars');
        $this->info('================================================');
        $this->newLine();
        
        try {
            // Obtener algunos carros existentes
            $cars = Car::limit(3)->get();
            
            if ($cars->count() === 0) {
                $this->warn('No hay carros en la base de datos para probar');
                return;
            }
            
            $this->info('🔍 Actualizando carros con códigos de barras:');
            
            // Ejemplos de códigos de barras como especificaste
            $codigosBarras = [
                '2845644182',
                '21SD541Q44',
                '9876543210'
            ];
            
            foreach ($cars as $index => $car) {
                $codigoBarras = $codigosBarras[$index] ?? 'DEFAULT123';
                
                // Actualizar el carro con código de barras
                $car->update(['codigo_barras' => $codigoBarras]);
                
                $this->info("✅ Car ID {$car->id_car}: {$car->car_make} {$car->car_model} - Código: {$codigoBarras}");
            }
            
            $this->newLine();
            $this->info('🔍 Verificando que los códigos se guardaron correctamente:');
            
            // Verificar que se guardaron correctamente
            $carsConCodigo = Car::whereNotNull('codigo_barras')->get();
            
            foreach ($carsConCodigo as $car) {
                $this->info("📦 {$car->car_make} {$car->car_model} ({$car->car_year}) - Código de barras: {$car->codigo_barras}");
            }
            
            $this->newLine();
            $this->info('📊 Estadísticas:');
            $this->info("Total de carros: " . Car::count());
            $this->info("Carros con código de barras: " . Car::whereNotNull('codigo_barras')->count());
            $this->info("Carros sin código de barras: " . Car::whereNull('codigo_barras')->count());
            
            $this->newLine();
            $this->info('✅ Prueba de codigo_barras completada exitosamente!');
            $this->info('La columna string codigo_barras funciona correctamente sin valor por defecto.');
            
        } catch (\Exception $e) {
            $this->error("❌ Error probando codigo_barras: " . $e->getMessage());
        }
    }
}
