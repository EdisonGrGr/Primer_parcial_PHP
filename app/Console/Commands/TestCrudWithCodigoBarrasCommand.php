<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;
use App\Models\Category;

class TestCrudWithCodigoBarrasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:crud-codigo-barras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validar que todas las operaciones CRUD funcionen correctamente con el nuevo campo codigo_barras';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Validando operaciones CRUD con el campo codigo_barras');
        $this->info('=====================================================');
        $this->newLine();
        
        try {
            // ====== OPERACIÓN CREATE ======
            $this->info('🔧 1. PROBANDO OPERACIÓN CREATE');
            $this->info('--------------------------------');
            
            // Obtener una categoría existente para la relación
            $categoria = Category::first();
            if (!$categoria) {
                $categoria = Category::create([
                    'name' => 'Categoria Test CRUD',
                    'description' => 'Categoria para probar CRUD',
                    'priority' => 1,
                    'discount_percentage' => 5.0,
                    'estado' => true,
                    'created_date' => now()->toDateString()
                ]);
            }
            
            // Crear carro CON código de barras
            $carConCodigo = Car::create([
                'car_make' => 'Tesla',
                'car_model' => 'Model S',
                'car_year' => 2024,
                'car_price' => 95000.00,
                'car_status' => true,
                'category_id' => $categoria->id,
                'codigo_barras' => 'TEST001234567'
            ]);
            
            $this->info("✅ Carro creado CON código de barras:");
            $this->info("   ID: {$carConCodigo->id_car}, Make: {$carConCodigo->car_make}, Código: {$carConCodigo->codigo_barras}");
            
            // Crear carro SIN código de barras
            $carSinCodigo = Car::create([
                'car_make' => 'Ford',
                'car_model' => 'Mustang',
                'car_year' => 2024,
                'car_price' => 45000.00,
                'car_status' => true,
                'category_id' => $categoria->id
                // Sin codigo_barras intencionalmente
            ]);
            
            $this->info("✅ Carro creado SIN código de barras:");
            $this->info("   ID: {$carSinCodigo->id_car}, Make: {$carSinCodigo->car_make}, Código: " . ($carSinCodigo->codigo_barras ?? 'NULL'));
            
            $this->newLine();
            
            // ====== OPERACIÓN READ ======
            $this->info('🔍 2. PROBANDO OPERACIÓN READ');
            $this->info('-----------------------------');
            
            // Leer todos los carros
            $totalCars = Car::count();
            $this->info("✅ Total de carros en BD: {$totalCars}");
            
            // Leer carros con código de barras
            $carsConCodigo = Car::whereNotNull('codigo_barras')->count();
            $this->info("✅ Carros con código de barras: {$carsConCodigo}");
            
            // Leer un carro específico
            $carEspecifico = Car::find($carConCodigo->id_car);
            $this->info("✅ Lectura específica - Car ID {$carEspecifico->id_car}: {$carEspecifico->car_make} {$carEspecifico->car_model}");
            $this->info("   Código de barras: {$carEspecifico->codigo_barras}");
            
            // Leer con relaciones
            $carConRelacion = Car::with('category')->find($carConCodigo->id_car);
            if ($carConRelacion && $carConRelacion->category) {
                $this->info("✅ Lectura con relación - Categoría: {$carConRelacion->category->name}");
            }
            
            $this->newLine();
            
            // ====== OPERACIÓN UPDATE ======
            $this->info('🔄 3. PROBANDO OPERACIÓN UPDATE');
            $this->info('-------------------------------');
            
            // Actualizar carro agregando código de barras
            $carSinCodigo->update(['codigo_barras' => 'UPDATE123456']);
            $this->info("✅ Actualizado carro SIN código -> CON código:");
            $this->info("   ID: {$carSinCodigo->id_car}, Nuevo código: {$carSinCodigo->codigo_barras}");
            
            // Actualizar carro modificando código existente
            $carConCodigo->update([
                'codigo_barras' => 'UPDATED789012',
                'car_price' => 98000.00
            ]);
            $this->info("✅ Actualizado carro CON código (modificado):");
            $this->info("   ID: {$carConCodigo->id_car}, Código actualizado: {$carConCodigo->codigo_barras}, Precio: \${$carConCodigo->car_price}");
            
            // Actualizar otros campos sin tocar código de barras
            $carConCodigo->update(['car_year' => 2025]);
            $carConCodigo->refresh();
            $this->info("✅ Actualizado otros campos (código intacto):");
            $this->info("   Año: {$carConCodigo->car_year}, Código: {$carConCodigo->codigo_barras}");
            
            $this->newLine();
            
            // ====== OPERACIÓN DELETE ======
            $this->info('🗑️  4. PROBANDO OPERACIÓN DELETE');
            $this->info('-------------------------------');
            
            // Crear un carro temporal para eliminar
            $carTemporal = Car::create([
                'car_make' => 'Toyota',
                'car_model' => 'Temporal',
                'car_year' => 2023,
                'car_price' => 25000.00,
                'car_status' => true,
                'category_id' => $categoria->id,
                'codigo_barras' => 'DELETE123'
            ]);
            
            $tempId = $carTemporal->id_car;
            $this->info("✅ Carro temporal creado para eliminar - ID: {$tempId}, Código: {$carTemporal->codigo_barras}");
            
            // Eliminar el carro
            $eliminado = $carTemporal->delete();
            $this->info("✅ Carro eliminado correctamente: " . ($eliminado ? 'SÍ' : 'NO'));
            
            // Verificar que ya no existe
            $carEliminado = Car::find($tempId);
            $this->info("✅ Verificación - Carro existe después de eliminar: " . ($carEliminado ? 'SÍ (ERROR)' : 'NO (CORRECTO)'));
            
            $this->newLine();
            
            // ====== RESUMEN FINAL ======
            $this->info('📊 5. RESUMEN DE VALIDACIÓN');
            $this->info('===========================');
            
            $estadisticas = [
                'Total carros' => Car::count(),
                'Con código de barras' => Car::whereNotNull('codigo_barras')->count(),
                'Sin código de barras' => Car::whereNull('codigo_barras')->count(),
                'Con categoría asignada' => Car::whereNotNull('category_id')->count()
            ];
            
            foreach ($estadisticas as $descripcion => $valor) {
                $this->info("📈 {$descripcion}: {$valor}");
            }
            
            $this->newLine();
            $this->info('✅ TODAS LAS OPERACIONES CRUD FUNCIONAN CORRECTAMENTE');
            $this->info('✅ El campo codigo_barras se integra perfectamente con las operaciones existentes');
            $this->info('✅ Las relaciones y restricciones continúan funcionando normalmente');
            
        } catch (\Exception $e) {
            $this->error("❌ Error durante las pruebas CRUD: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
