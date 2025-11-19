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
        Schema::create('marcaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ESP_Id')->nullable();; // Espacio de parqueo
            $table->unsignedBigInteger('VEH_Id'); // Vehículo
            $table->unsignedBigInteger('USU_Id'); // Usuario que hizo el registro
        
            $table->timestamp('fecha_entrada')->useCurrent(); // Fecha y hora actual por defecto
            $table->timestamp('fecha_salida')->nullable(); // Queda null hasta que se registre salida
        
            $table->decimal('monto_total', 10, 2)->nullable()->default(0);
            $table->enum('estado', ['activo', 'finalizado', 'cancelado'])->default('activo');
            $table->enum('tipo_marcacion', ['hora', 'dia', 'semana', 'mes'])->default('dia');
            $table->timestamps();
        
            // Claves foráneas
            $table->foreign('ESP_Id')->references('id')->on('espacios_parqueo')->onDelete('cascade');
            $table->foreign('VEH_Id')->references('id')->on('vehiculos')->onDelete('cascade');
            $table->foreign('USU_Id')->references('id')->on('users')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marcaciones');
    }
};
