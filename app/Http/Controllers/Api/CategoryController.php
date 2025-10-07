<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener categorías paginadas ordenadas por prioridad y luego por nombre
        $categories = Category::orderBy('priority', 'asc')
                             ->orderBy('name', 'asc')
                             ->paginate(10);
        
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);
        
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        
        return response()->json(null, 204);
    }

    /**
     * Listar todas las categorías activas con sus carros relacionados.
     * 
     * Punto 8.1: Método adicional que lista categorías con estado = true
     * e incluye los registros de carros que están relacionados con cada categoría.
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function active()
    {
        // Obtener categorías activas con sus carros relacionados usando eager loading
        $activeCategories = Category::with(['cars' => function ($query) {
                                        // Opcional: ordenar carros por nombre
                                        $query->orderBy('car_make', 'asc')
                                              ->orderBy('car_model', 'asc');
                                    }])
                                    ->where('estado', true)
                                    ->orderBy('priority', 'asc')
                                    ->orderBy('name', 'asc')
                                    ->get();

        return CategoryResource::collection($activeCategories);
    }

    /**
     * Método alternativo: Listar categorías activas con carros disponibles solamente.
     * 
     * Este método es útil cuando solo se quieren mostrar carros que están disponibles
     * para venta dentro de las categorías activas.
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function activeWithAvailableCars()
    {
        // Obtener categorías activas con solo carros disponibles
        $activeCategories = Category::with(['cars' => function ($query) {
                                        $query->where('car_status', true)
                                              ->orderBy('car_make', 'asc')
                                              ->orderBy('car_model', 'asc');
                                    }])
                                    ->where('estado', true)
                                    ->orderBy('priority', 'asc')
                                    ->orderBy('name', 'asc')
                                    ->get();

        return CategoryResource::collection($activeCategories);
    }

    /**
     * Método con paginación: Listar categorías activas con carros (paginado).
     * 
     * Para casos donde hay muchas categorías, este método permite paginar
     * manteniendo la funcionalidad de eager loading.
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function activePaginated()
    {
        // Obtener categorías activas paginadas con sus carros
        $activeCategories = Category::with(['cars' => function ($query) {
                                        $query->orderBy('car_make', 'asc')
                                              ->orderBy('car_model', 'asc');
                                    }])
                                    ->where('estado', true)
                                    ->orderBy('priority', 'asc')
                                    ->orderBy('name', 'asc')
                                    ->paginate(5); // 5 categorías por página

        return CategoryResource::collection($activeCategories);
    }
}
