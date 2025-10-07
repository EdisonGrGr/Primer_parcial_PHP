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
        Schema::table('cars', function (Blueprint $table) {
            // Agregar la clave foránea category_id
            $table->unsignedBigInteger('category_id')->nullable()->after('car_status');
            
            // Definir la clave foránea con restricciones
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Si se elimina la categoría, se pone null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Eliminar primero la clave foránea
            $table->dropForeign(['category_id']);
            // Luego eliminar la columna
            $table->dropColumn('category_id');
        });
    }
};
