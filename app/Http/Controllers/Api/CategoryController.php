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
     * Display active categories with related cars.
     */
    public function active()
    {
        $activeCategories = Category::with(['cars' => function ($query) {
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
     * Display active categories with available cars only.
     */
    public function activeWithAvailableCars()
    {
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
     * Display active categories with cars (paginated).
     */
    public function activePaginated()
    {
        $activeCategories = Category::with(['cars' => function ($query) {
                                        $query->orderBy('car_make', 'asc')
                                              ->orderBy('car_model', 'asc');
                                    }])
                                    ->where('estado', true)
                                    ->orderBy('priority', 'asc')
                                    ->orderBy('name', 'asc')
                                    ->paginate(5);

        return CategoryResource::collection($activeCategories);
    }
}
