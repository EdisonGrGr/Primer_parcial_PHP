<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_car,
            'make' => $this->car_make,
            'model' => $this->car_model,
            'year' => $this->car_year,
            'price' => $this->car_price,
            'status' => $this->car_status,
            'category_id' => $this->category_id,
            'codigo_barras' => $this->codigo_barras,
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}