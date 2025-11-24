<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_car'; 
    public $incrementing = true;
    protected $keyType = 'int';

    // Si el nombre de la tabla fuera distinto: protected $table = 'cars';

    protected $fillable = [
        'car_make',
        'car_model',
        'car_year',
        'car_price',
        'color',
        'car_status',
        'category_id',
        'codigo_barras',
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

    // ==========================================
    // RELACIONES ELOQUENT
    // ==========================================

    /**
     * Relación Many-to-One: Un carro pertenece a una categoría
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // ==========================================
    // QUERY SCOPES
    // ==========================================

    /**
     * Scope: Filtrar carros activos (disponibles)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('car_status', true);
    }

    /**
     * Scope: Filtrar carros por categoría activa
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithActiveCategory($query)
    {
        return $query->whereHas('category', function ($query) {
            $query->where('estado', true);
        });
    }

    /**
     * Scope: Filtrar carros con código de barras
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithBarcode($query)
    {
        return $query->whereNotNull('codigo_barras');
    }

    /**
     * Scope: Filtrar carros por rango de años
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $from
     * @param int $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByYearRange($query, $from, $to)
    {
        return $query->whereBetween('car_year', [$from, $to]);
    }

    /**
     * Scope: Filtrar carros por rango de precios
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $min
     * @param float $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('car_price', [$min, $max]);
    }

    // ==========================================
    // ACCESSORS & MUTATORS
    // ==========================================

    /**
     * Accessor: Obtener el nombre completo del carro
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->car_make} {$this->car_model} ({$this->car_year})";
    }

    /**
     * Accessor: Obtener el precio formateado
     * 
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->car_price, 2);
    }

    /**
     * Accessor: Verificar si el carro está disponible
     * 
     * @return bool
     */
    public function getIsAvailableAttribute()
    {
        return $this->car_status === true;
    }

    /**
     * Accessor: Obtener la edad del carro
     * 
     * @return int
     */
    public function getAgeAttribute()
    {
        return date('Y') - $this->car_year;
    }
}
