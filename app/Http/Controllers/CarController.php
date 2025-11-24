<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarController extends Controller
{
    /**
     * Muestra el listado de vehículos con filtros opcionales.
     * Este método implementa filtrado desde el backend:
     * 1. Recibe los parámetros 'search' (búsqueda por texto) y 'category' (filtro de categoría) 
     *    desde la URL usando $request->input()
     * 2. Los filtros se aplican directamente en la consulta a la base de datos antes de paginar,
     *    solo se traen de la BD los registros que cumplen los criterios
     * 3. La búsqueda permite encontrar vehículos por marca (car_make) o modelo (car_model)
     *    usando ILIKE para que sea case-insensitive (no distingue mayúsculas/minúsculas)
     * 4. Si el usuario selecciona una categoría específica, se filtra por category_id
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryFilter = $request->input('category');
        
        // Query builder con eager loading de la categoría
        $query = Car::with('category');
        
        // Si hay término de búsqueda, filtrar por marca o modelo
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('car_make', 'ILIKE', '%' . $search . '%')
                  ->orWhere('car_model', 'ILIKE', '%' . $search . '%');
            });
        }
        
        
        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }
        
        
        $cars = $query->orderBy('car_make', 'asc')
                     ->orderBy('car_model', 'asc')
                     ->paginate(15)
                     ->withQueryString();
        
        
        $categories = Category::where('estado', true)
                             ->orderBy('name', 'asc')
                             ->get(['id', 'name']);
        
        return Inertia::render('Cars/Index', [
            'cars' => $cars,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $categoryFilter,
            ],
        ]);
    }

    
    public function create()
    {
        
        $categories = Category::where('estado', true)
                             ->orderBy('name', 'asc')
                             ->get(['id', 'name']);
        
        return Inertia::render('Cars/Create', [
            'categories' => $categories,
        ]);
    }

    
    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();
        
        
        Car::create($data);
        
        return redirect()->route('cars.index')
            ->with('success', '¡Vehículo creado exitosamente!');
    }

    
    public function show(Car $car)
    {
        
        $car->load('category');
        
        return Inertia::render('Cars/Show', [
            'car' => $car,
            'category' => $car->category,
        ]);
    }

    
    public function edit(Car $car)
    {
        $categories = Category::where('estado', true)
                             ->orderBy('name', 'asc')
                             ->get(['id', 'name']);
        
        return Inertia::render('Cars/Edit', [
            'car' => $car,
            'categories' => $categories,
        ]);
    }

    
    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();
        
        $car->update($data);
        
        return redirect()->route('cars.index')
            ->with('success', '¡Vehículo actualizado exitosamente!');
    }

    
    public function destroy(Car $car)
    {
        try {
            $car->delete();
            
            return redirect()->route('cars.index')
                ->with('success', '¡Vehículo eliminado exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('cars.index')
                ->with('error', 'No se puede eliminar el vehículo.');
        }
    }
}
