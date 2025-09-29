<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            // 'sometimes' permite actualizaciones parciales
            'car_make'  => 'sometimes|required|string|max:100',
            'car_model' => 'sometimes|required|string|max:100',
            'car_year'  => "sometimes|required|integer|min:1900|max:$currentYear",
            'car_price' => 'sometimes|required|numeric|min:0',
            'car_status'=> 'sometimes|boolean',
        ];
    }
}