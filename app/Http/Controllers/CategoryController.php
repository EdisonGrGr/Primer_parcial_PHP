<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Muestra el listado de categorías con opción de búsqueda por nombre. 
     * Este método implementa filtrado desde el backend:
     * 1. Recibe el parámetro 'search' desde la URL usando $request->input('search')
     *    Por ejemplo: /categories?search=sedan
     * 2. El filtro se aplica con ILIKE directamente en la base de datos antes de paginar,
     *    la BD filtra los datos y solo envía los resultados que coinciden
     * 3. Solo trae de la base de datos los registros que coinciden con el criterio,
     *    por ejemplo: si busco "SUV", solo vienen las categorías que tienen "SUV" en el nombre
     * 4. Usa withCount('cars') para contar cuántos vehículos tiene cada categoría
     *    sin necesidad de cargar todos los vehículos
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        
        $query = Category::withCount('cars');
        
        
        if ($search) {
            $query->where('name', 'ILIKE', '%' . $search . '%');
        }
        
        // Ordenar y paginar resultados
        $categories = $query->orderBy('priority', 'asc')
                           ->orderBy('name', 'asc')
                           ->paginate(15)
                           ->withQueryString(); 
        
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'filters' => [
                'search' => $search, 
            ],
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created category in storage.
     * 

     */
    public function store(StoreCategoryRequest $request)
    {
        
        $data = $request->validated();
        
        
        if (!isset($data['created_date'])) {
            $data['created_date'] = now()->toDateString();
        }
        
        
        Category::create($data);
        
        
        return redirect()->route('categories.index')
            ->with('success', '¡Categoría creada exitosamente!');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Display the specified category with its relationships.
     * 
     */
    public function show(Category $category)
    {
        
        $category->load('cars');
        
        return Inertia::render('Categories/Show', [
            'category' => $category,
            'cars' => $category->cars, 
        ]);
    }

    /**
     * Update the specified category in storage.
     * 
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        
        $data = $request->validated();
        
        
        $category->update($data);
        
        
        return redirect()->route('categories.index')
            ->with('success', '¡Categoría actualizada exitosamente!');
    }

    /**
     * Remove the specified category from storage.
     * 
     */
    public function destroy(Category $category)
    {
        
        if ($category->cars()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene ' . $category->cars()->count() . ' vehículo(s) asociado(s).');
        }
        
        try {
            
            $category->delete();
            
            
            return redirect()->route('categories.index')
                ->with('success', '¡Categoría eliminada exitosamente!');
        } catch (\Exception $e) {
            
            return redirect()->route('categories.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}
