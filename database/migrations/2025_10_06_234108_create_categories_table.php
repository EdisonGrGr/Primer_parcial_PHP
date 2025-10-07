<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                                           // bigint (PK)
            $table->string('name', 100);                           // varchar - Nombre de la categoría
            $table->text('description')->nullable();                // text - Descripción detallada
            $table->integer('priority')->default(1);               // integer - Prioridad de la categoría
            $table->decimal('discount_percentage', 5, 2)->default(0.00); // decimal - Porcentaje de descuento
            $table->boolean('estado')->default(true);              // boolean - Estado activo/inactivo (requerido)
            $table->date('created_date')->default(now());          // date - Fecha de creación
            $table->timestamps();                                   // datetime - created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
