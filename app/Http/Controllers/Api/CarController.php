<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;

class CarController extends Controller
{
    // GET /api/cars
    public function index()
    {
        // paginado para no traer todo si hay muchos registros
        // Incluimos la relación de categoría para mostrar información completa
        $cars = Car::with('category')->orderBy('id_car', 'desc')->paginate(10);
        return CarResource::collection($cars);
    }

    // POST /api/cars
    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();
        $car = Car::create($data);
        return new CarResource($car);
    }

    // GET /api/cars/{car}
    public function show(Car $car)
    {
        // Cargar la relación de categoría para incluir toda su información
        $car->load('category');
        return new CarResource($car);
    }

    // PUT/PATCH /api/cars/{car}
    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();
        $car->update($data);
        return new CarResource($car);
    }

    // DELETE /api/cars/{car}
    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json(null, 204);
    }
}
