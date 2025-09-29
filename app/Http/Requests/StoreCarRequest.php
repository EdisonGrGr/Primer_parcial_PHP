<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        ];
    }
}
