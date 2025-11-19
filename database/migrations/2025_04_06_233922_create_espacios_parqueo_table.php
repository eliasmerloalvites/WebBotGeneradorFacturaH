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
        Schema::create('espacios_parqueo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('COC_Id'); // FK a cocheras
            $table->string('codigo', 10);
            $table->unsignedBigInteger('TIV_Id')->nullable(); // FK a tipo de vehículo u otro
            $table->enum('state', ['disponible', 'pendiente','ocupado', 'mantenimiento'])->default('disponible');
            $table->boolean('estado')->default(1); // 1 = activo, 0 = inactivo
            $table->timestamps();
    
            // Llaves foráneas
            $table->foreign('COC_Id')->references('id')->on('cocheras')->onDelete('cascade');
            $table->foreign('TIV_Id')->references('id')->on('tipos_vehiculos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacios_parqueo');
    }
};
