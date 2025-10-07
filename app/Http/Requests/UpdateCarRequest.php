<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = date('Y');
        $carId = $this->route('car')->id_car ?? null;

        return [
            
            'car_make'  => 'sometimes|required|string|max:100',
            'car_model' => 'sometimes|required|string|max:100',
            'car_year'  => "sometimes|required|integer|min:1900|max:$currentYear",
            'car_price' => 'sometimes|required|numeric|min:0',
            'car_status'=> 'sometimes|boolean',
            
            
            'category_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    
                    $query->where('estado', true);
                }),
            ],
            
            'codigo_barras' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9_-]+$/', 
                Rule::unique('cars', 'codigo_barras')->ignore($carId, 'id_car'),
            ],
        ];
    }

    
    public function messages(): array
    {
        return [
            'category_id.exists' => 'La categoría seleccionada no existe o está inactiva.',
            'category_id.integer' => 'El ID de categoría debe ser un número entero.',
            'codigo_barras.unique' => 'Este código de barras ya está en uso por otro vehículo.',
            'codigo_barras.regex' => 'El código de barras solo puede contener letras, números, guiones y guiones bajos.',
            'car_make.required' => 'La marca del vehículo es obligatoria.',
            'car_model.required' => 'El modelo del vehículo es obligatorio.',
            'car_year.required' => 'El año del vehículo es obligatorio.',
            'car_year.min' => 'El año debe ser mayor o igual a 1900.',
            'car_year.max' => 'El año no puede ser mayor al año actual.',
            'car_price.required' => 'El precio del vehículo es obligatorio.',
            'car_price.min' => 'El precio debe ser mayor o igual a 0.',
        ];
    }

    
    public function attributes(): array
    {
        return [
            'car_make' => 'marca',
            'car_model' => 'modelo',
            'car_year' => 'año',
            'car_price' => 'precio',
            'car_status' => 'estado',
            'category_id' => 'categoría',
            'codigo_barras' => 'código de barras',
        ];
    }
}