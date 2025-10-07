<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'priority',
        'discount_percentage',
        'estado',
        'created_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado' => 'boolean',
        'priority' => 'integer',
        'discount_percentage' => 'decimal:2',
        'created_date' => 'date',
    ];

    // ==========================================
    // RELACIONES ELOQUENT
    // ==========================================

    /**
     * Relación One-to-Many: Una categoría tiene muchos carros
     * 
     * Según la documentación de Laravel, hasMany establece una relación 1:N
     * donde una categoría puede tener múltiples carros asociados.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cars()
    {
        return $this->hasMany(Car::class, 'category_id', 'id');
    }

    /**
     * Relación: Obtener solo carros activos de esta categoría
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeCars()
    {
        return $this->hasMany(Car::class, 'category_id', 'id')
                    ->where('car_status', true);
    }

    /**
     * Relación: Obtener carros con código de barras de esta categoría
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carsWithBarcode()
    {
        return $this->hasMany(Car::class, 'category_id', 'id')
                    ->whereNotNull('codigo_barras');
    }

    // ==========================================
    // QUERY SCOPES
    // ==========================================

    /**
     * Scope: Filtrar solo categorías activas
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('estado', true);
    }

    /**  
     * Scope: Categorías con carros asociados
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCars($query)
    {
        return $query->has('cars');
    }

    /**
     * Scope: Categorías ordenadas por prioridad
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    // ==========================================
    // ACCESSORS & MUTATORS
    // ==========================================

    /**
     * Accessor: Obtener el número de carros en esta categoría
     * 
     * @return int
     */
    public function getCarsCountAttribute()
    {
        return $this->cars()->count();
    }

    /**
     * Accessor: Obtener el nombre formateado de la categoría
     * 
     * @return string
     */
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->name));
    }

    /**
     * Accessor: Verificar si la categoría está activa
     * 
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        return $this->estado === true;
    }

    /**
     * Accessor: Obtener el porcentaje de descuento formateado
     * 
     * @return string
     */
    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount_percentage, 2) . '%';
    }
}
