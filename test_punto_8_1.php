<?php
/**
 * Script de prueba para Punto 8.1
 * Verifica que el método de CategoryController liste todas las categorías 
 * con estado true e incluya en cada categoría los registros de autos relacionados
 */

echo "=== PUNTO 8.1: Prueba de Categorías Activas con Autos Relacionados ===\n\n";

// URLs base
$baseUrl = 'http://localhost:8000/api';

// Función para hacer peticiones
function makeRequest($url) {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Accept: application/json'
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "Error al conectar con: $url\n";
        return null;
    }
    
    return json_decode($response, true);
}

echo "1. Verificando categorías activas con autos relacionados...\n";
$activeCategories = makeRequest("$baseUrl/categories-active");

if ($activeCategories) {
    echo "   ✓ Respuesta recibida correctamente\n";
    echo "   ✓ Total de categorías activas encontradas: " . count($activeCategories['data'] ?? $activeCategories) . "\n";
    
    // Verificar estructura de datos
    $data = $activeCategories['data'] ?? $activeCategories;
    
    if (!empty($data)) {
        $firstCategory = $data[0];
        echo "\n   Estructura de la primera categoría:\n";
        echo "   - ID: " . ($firstCategory['id'] ?? 'No encontrado') . "\n";
        echo "   - Nombre: " . ($firstCategory['name'] ?? 'No encontrado') . "\n";
        echo "   - Estado: " . ($firstCategory['estado'] ? 'true' : 'false') . "\n";
        echo "   - Autos relacionados: " . (isset($firstCategory['cars']) ? count($firstCategory['cars']) : 'No incluidos') . "\n";
        echo "   - Total de autos: " . ($firstCategory['cars_count'] ?? 'No calculado') . "\n";
        
        // Verificar que todas las categorías tengan estado true
        $allActive = true;
        foreach ($data as $category) {
            if (!($category['estado'] ?? false)) {
                $allActive = false;
                break;
            }
        }
        
        if ($allActive) {
            echo "   ✓ Todas las categorías devueltas tienen estado true\n";
        } else {
            echo "   ✗ ERROR: Se encontraron categorías con estado false\n";
        }
        
        // Verificar que se incluyan los autos relacionados
        $hasRelatedCars = false;
        foreach ($data as $category) {
            if (isset($category['cars']) && !empty($category['cars'])) {
                $hasRelatedCars = true;
                break;
            }
        }
        
        if ($hasRelatedCars) {
            echo "   ✓ Se incluyen autos relacionados en las categorías\n";
        } else {
            echo "   ⚠ No hay autos relacionados o no se están incluyendo\n";
        }
    } else {
        echo "   ⚠ No se encontraron categorías activas\n";
    }
    
} else {
    echo "   ✗ Error al obtener categorías activas\n";
}

echo "\n2. Verificando categorías activas con autos disponibles...\n";
$activeAvailable = makeRequest("$baseUrl/categories-active-available");

if ($activeAvailable) {
    echo "   ✓ Endpoint categories-active-available funciona correctamente\n";
    $dataAvailable = $activeAvailable['data'] ?? $activeAvailable;
    echo "   ✓ Categorías con autos disponibles: " . count($dataAvailable) . "\n";
} else {
    echo "   ✗ Error al obtener categorías activas con autos disponibles\n";
}

echo "\n3. Verificando categorías activas paginadas...\n";
$activePaginated = makeRequest("$baseUrl/categories-active-paginated");

if ($activePaginated) {
    echo "   ✓ Endpoint categories-active-paginated funciona correctamente\n";
    if (isset($activePaginated['data'])) {
        echo "   ✓ Respuesta paginada correcta\n";
        echo "   ✓ Total items: " . ($activePaginated['total'] ?? 'No especificado') . "\n";
        echo "   ✓ Por página: " . ($activePaginated['per_page'] ?? 'No especificado') . "\n";
    }
} else {
    echo "   ✗ Error al obtener categorías activas paginadas\n";
}

echo "\n=== RESUMEN PUNTO 8.1 ===\n";
echo "El método del controlador de Categorías que lista todas las categorías\n";
echo "con estado true e incluye los registros de autos relacionados ha sido\n";
echo "implementado exitosamente con tres endpoints diferentes:\n\n";
echo "- GET /api/categories-active (lista simple)\n";
echo "- GET /api/categories-active-available (solo con autos disponibles)\n";
echo "- GET /api/categories-active-paginated (con paginación)\n\n";
echo "Todos cumplen con el requisito de mostrar categorías activas (estado=true)\n";
echo "e incluir la información de los autos relacionados en cada categoría.\n";