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
    Schema::create('cars', function (Blueprint $table) {
        $table->bigIncrements('id_car');              // PK bigint
        $table->string('car_make', 100);              // marca
        $table->string('car_model', 100);             // modelo
        $table->integer('car_year');                  // año
        $table->double('car_price', 15, 2)->default(0); // precio
        $table->boolean('car_status')->default(true); // disponible? (true/false)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
