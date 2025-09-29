<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_car'; // nombre de la PK
    public $incrementing = true;
    protected $keyType = 'int';

    // Si el nombre de la tabla fuera distinto: protected $table = 'cars';

    protected $fillable = [
        'car_make',
        'car_model',
        'car_year',
        'car_price',
        'car_status',
    ];

    protected $casts = [
        'car_year' => 'integer',
        'car_price' => 'float',
        'car_status' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id_car';
    }
}
