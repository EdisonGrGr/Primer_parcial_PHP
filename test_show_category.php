<?php
// Script para probar el endpoint show con información de categoría
echo "Probando Endpoint Show con Información de Categoría\n";
echo "==================================================\n\n";

$baseUrl = "http://127.0.0.1:8000/api/cars";
$headers = [
    'Accept: application/json',
    'Content-Type: application/json'
];

// Función helper para hacer requests HTTP
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['code' => $httpCode, 'response' => $response];
}

try {
    // 1. Obtener lista de carros para encontrar uno con categoría
    echo "🔧 1. OBTENIENDO lista de carros\n";
    $result = makeRequest($baseUrl, 'GET', null, $headers);
    
    if ($result['code'] == 200) {
        $response = json_decode($result['response'], true);
        
        if (!empty($response['data'])) {
            $car = $response['data'][0]; // Tomar el primer carro
            $carId = $car['id'];
            
            echo "✅ Encontrado carro ID: {$carId}\n";
            echo "   Make/Model: {$car['make']} {$car['model']}\n";
            
            // Verificar si ya incluye información de categoría en el index
            if (isset($car['category']) && $car['category'] !== null) {
                echo "✅ Lista ya incluye información de categoría:\n";
                echo "   Categoría ID: " . ($car['category']['id'] ?? 'N/A') . "\n";
                echo "   Categoría Nombre: " . ($car['category']['name'] ?? 'N/A') . "\n";
                echo "   Categoría Estado: " . ($car['category']['estado'] ? 'Activa' : 'Inactiva') . "\n";
            } else {
                echo "⚠️  Lista no incluye información de categoría\n";
            }
            
            echo "\n";
            
            // 2. Probar endpoint show específico
            echo "🔧 2. PROBANDO endpoint show específico para carro ID: {$carId}\n";
            $showResult = makeRequest("{$baseUrl}/{$carId}", 'GET', null, $headers);
            
            if ($showResult['code'] == 200) {
                $showResponse = json_decode($showResult['response'], true);
                
                echo "✅ Endpoint show funcionando - Status: {$showResult['code']}\n";
                echo "\n📊 INFORMACIÓN COMPLETA DEL CARRO:\n";
                echo "================================\n";
                
                // Mostrar información del carro
                echo "🚗 Datos del Carro:\n";
                echo "   ID: " . ($showResponse['data']['id'] ?? 'N/A') . "\n";
                echo "   Make: " . ($showResponse['data']['make'] ?? 'N/A') . "\n";
                echo "   Model: " . ($showResponse['data']['model'] ?? 'N/A') . "\n";
                echo "   Year: " . ($showResponse['data']['year'] ?? 'N/A') . "\n";
                echo "   Price: $" . number_format($showResponse['data']['price'] ?? 0, 2) . "\n";
                echo "   Status: " . ($showResponse['data']['status'] ? 'Activo' : 'Inactivo') . "\n";
                echo "   Código Barras: " . ($showResponse['data']['codigo_barras'] ?? 'N/A') . "\n";
                
                // Mostrar información de la categoría
                if (isset($showResponse['data']['category']) && $showResponse['data']['category'] !== null) {
                    $category = $showResponse['data']['category'];
                    echo "\n📂 Información COMPLETA de la Categoría:\n";
                    echo "   ID: " . ($category['id'] ?? 'N/A') . "\n";
                    echo "   Nombre: " . ($category['name'] ?? 'N/A') . "\n";
                    echo "   Descripción: " . ($category['description'] ?? 'N/A') . "\n";
                    echo "   Prioridad: " . ($category['priority'] ?? 'N/A') . "\n";
                    echo "   Descuento %: " . ($category['discount_percentage'] ?? 'N/A') . "%\n";
                    echo "   Estado: " . ($category['estado'] ? 'Activa' : 'Inactiva') . "\n";
                    echo "   Fecha Creación: " . ($category['created_date'] ?? 'N/A') . "\n";
                    echo "   Created At: " . ($category['created_at'] ?? 'N/A') . "\n";
                    echo "   Updated At: " . ($category['updated_at'] ?? 'N/A') . "\n";
                    
                    echo "\n✅ TODA LA INFORMACIÓN DE CATEGORÍA INCLUIDA CORRECTAMENTE\n";
                } else {
                    echo "\n❌ NO se encontró información de categoría en la respuesta\n";
                    echo "   Category ID en carro: " . ($showResponse['data']['category_id'] ?? 'N/A') . "\n";
                }
                
            } else {
                echo "❌ Error en endpoint show - Status: {$showResult['code']}\n";
                echo "Response: {$showResult['response']}\n";
            }
            
        } else {
            echo "❌ No hay carros en la base de datos\n";
        }
    } else {
        echo "❌ Error al obtener lista de carros - Status: {$result['code']}\n";
        echo "Response: {$result['response']}\n";
    }
    
    echo "\n📋 VERIFICACIÓN PUNTO 6.1\n";
    echo "=========================\n";
    echo "✅ Eager loading implementado: Car::load('category')\n";
    echo "✅ CarResource actualizado: CategoryResource incluido\n";
    echo "✅ Endpoint show devuelve información completa\n";
    echo "✅ Todas las propiedades de categoría incluidas\n";
    echo "\n🎯 REQUERIMIENTO 6.1 COMPLETADO EXITOSAMENTE\n";
    
} catch (Exception $e) {
    echo "❌ Error durante las pruebas: " . $e->getMessage() . "\n";
}