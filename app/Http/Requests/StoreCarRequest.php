<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // si no usas auth, deja true
    }

    public function rules(): array
    {
        $currentYear = date('Y');

        return [
            'car_make'  => 'required|string|max:100',
            'car_model' => 'required|string|max:100',
            'car_year'  => "required|integer|min:1900|max:$currentYear",
            'car_price' => 'required|numeric|min:0',
            'car_status'=> 'sometimes|boolean',
            
            // Validación de FK según documentación Laravel
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    // Solo categorías activas
                    $query->where('estado', true);
                }),
            ],
            
            'codigo_barras' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9_-]+$/', // Solo alfanuméricos, guiones y guiones bajos
                Rule::unique('cars', 'codigo_barras'),
            ],
        ];
    }

    /**
     * Mensajes de error personalizados según documentación Laravel
     */
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

    /**
     * Nombres de atributos personalizados
     */
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
