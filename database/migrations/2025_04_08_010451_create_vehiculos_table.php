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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->unique();
            $table->unsignedBigInteger('TIV_Id');
            $table->unsignedBigInteger('CLI_Id')->nullable();
            $table->string('color')->nullable();   // ahora permite null
            $table->string('marca')->nullable();   // ahora permite null
            $table->string('modelo')->nullable();  // ahora permite null
            $table->timestamps();
        
            $table->foreign('TIV_Id')->references('id')->on('tipos_vehiculos')->onDelete('cascade');
            $table->foreign('CLI_Id')->references('id')->on('clientes')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
