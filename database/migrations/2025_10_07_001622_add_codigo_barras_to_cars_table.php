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
            // Agregar columna codigo_barras de tipo string
            // SIN valor por defecto (nullable para registros existentes)
            $table->string('codigo_barras')->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Eliminar la columna codigo_barras
            $table->dropColumn('codigo_barras');
        });
    }
};
