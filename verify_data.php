<?php
/**
 * Verificación específica de datos para punto 8.1
 */

echo "=== Verificación detallada de datos ===\n\n";

$baseUrl = 'http://localhost:8000/api';

function makeRequest($url) {
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Accept: application/json'
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    return $response ? json_decode($response, true) : null;
}

// Verificar una categoría específica con show para comparar
echo "1. Obteniendo primera categoría específica...\n";
$categories = makeRequest("$baseUrl/categories");
if ($categories && !empty($categories['data'])) {
    $firstCategoryId = $categories['data'][0]['id'];
    echo "   ID de primera categoría: $firstCategoryId\n";
    
    $categoryDetails = makeRequest("$baseUrl/categories/$firstCategoryId");
    if ($categoryDetails) {
        echo "   Detalles vía show():\n";
        echo "   - Nombre: " . ($categoryDetails['name'] ?? 'N/A') . "\n";
        echo "   - Estado: " . ($categoryDetails['estado'] ? 'true' : 'false') . "\n";
        echo "   - Tiene cars?: " . (isset($categoryDetails['cars']) ? 'Sí' : 'No') . "\n";
        if (isset($categoryDetails['cars'])) {
            echo "   - Cantidad cars: " . count($categoryDetails['cars']) . "\n";
        }
    }
}

echo "\n2. Verificando endpoint active para la misma categoría...\n";
$activeCategories = makeRequest("$baseUrl/categories-active");
if ($activeCategories) {
    $data = $activeCategories['data'] ?? $activeCategories;
    $found = false;
    foreach ($data as $category) {
        if ($category['id'] == $firstCategoryId) {
            $found = true;
            echo "   Categoría encontrada en active():\n";
            echo "   - Nombre: " . ($category['name'] ?? 'N/A') . "\n";
            echo "   - Estado: " . ($category['estado'] ? 'true' : 'false') . "\n";
            echo "   - Tiene cars?: " . (isset($category['cars']) ? 'Sí' : 'No') . "\n";
            if (isset($category['cars'])) {
                echo "   - Cantidad cars: " . count($category['cars']) . "\n";
                if (!empty($category['cars'])) {
                    echo "   - Primer auto: " . json_encode($category['cars'][0], JSON_PRETTY_PRINT) . "\n";
                }
            }
            break;
        }
    }
    
    if (!$found) {
        echo "   La categoría $firstCategoryId no se encontró en active() (probablemente estado=false)\n";
        echo "   Mostrando primera categoría activa encontrada:\n";
        if (!empty($data)) {
            $firstActive = $data[0];
            echo "   - ID: " . $firstActive['id'] . "\n";
            echo "   - Nombre: " . ($firstActive['name'] ?? 'N/A') . "\n";
            echo "   - Estado: " . ($firstActive['estado'] ? 'true' : 'false') . "\n";
            echo "   - Tiene cars?: " . (isset($firstActive['cars']) ? 'Sí' : 'No') . "\n";
            if (isset($firstActive['cars']) && !empty($firstActive['cars'])) {
                echo "   - Cantidad cars: " . count($firstActive['cars']) . "\n";
                echo "   - Primer auto ID: " . ($firstActive['cars'][0]['id'] ?? 'N/A') . "\n";
                echo "   - Primer auto marca: " . ($firstActive['cars'][0]['make'] ?? 'N/A') . "\n";
            }
        }
    }
}

echo "\n3. Conteo general de autos por categoría...\n";
$allCars = makeRequest("$baseUrl/cars");
if ($allCars) {
    $carData = $allCars['data'] ?? $allCars;
    $categoryCounts = [];
    foreach ($carData as $car) {
        $catId = $car['category_id'] ?? null;
        if ($catId) {
            $categoryCounts[$catId] = ($categoryCounts[$catId] ?? 0) + 1;
        }
    }
    
    echo "   Total de autos: " . count($carData) . "\n";
    echo "   Distribución por categoría:\n";
    foreach ($categoryCounts as $catId => $count) {
        echo "   - Categoría $catId: $count autos\n";
    }
}