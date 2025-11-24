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

        return [
            'car_make'  => 'required|string|max:100',
            'car_model' => 'required|string|max:100',
            'car_year'  => "required|integer|min:1900|max:$currentYear",
            'car_price' => 'required|numeric|min:0',
            'color' => 'required|string|max:50',
            'car_status'=> 'sometimes|boolean',
            
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('estado', true);
                }),
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