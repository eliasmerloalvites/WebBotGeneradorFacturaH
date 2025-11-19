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
        Schema::create('tipos_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->comment('Ej: Moto, Auto, Camioneta, etc.');
            $table->string('tamaño')->nullable()->comment('Tamaño del vehículo (opcional)');
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable()->comment('Ruta o nombre de archivo de la imagen');
            $table->boolean('estado')->default(true); // true = activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_vehiculos');
    }
};
