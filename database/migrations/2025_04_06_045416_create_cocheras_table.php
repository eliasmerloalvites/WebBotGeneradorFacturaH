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
        Schema::create('cocheras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('EMP_Id')->constrained('empresas')->onDelete('cascade'); // Relación con la tabla empresa, usando EMP_Id
            $table->string('nombre');
            $table->string('direccion');
            $table->decimal('latitud', 10, 8); // Para la latitud
            $table->decimal('longitud', 11, 8); // Para la longitud
            $table->enum('estado', ['activa', 'inactiva'])->default('activa'); // Estado de la cochera
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cocheras');
    }
};
