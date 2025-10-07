<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|integer|min:1|max:100',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'estado' => 'required|boolean',
            'created_date' => 'sometimes|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            'name.max' => 'El nombre no puede tener más de 100 caracteres.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.min' => 'La prioridad debe ser mayor a 0.',
            'priority.max' => 'La prioridad no puede ser mayor a 100.',
            'discount_percentage.required' => 'El porcentaje de descuento es obligatorio.',
            'discount_percentage.min' => 'El descuento no puede ser negativo.',
            'discount_percentage.max' => 'El descuento no puede ser mayor al 100%.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
        ];
    }
}
