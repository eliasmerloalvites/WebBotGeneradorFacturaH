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
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('COC_Id');
            $table->unsignedBigInteger('TIV_Id');
            $table->decimal('precio_hora', 8, 2);
            $table->decimal('precio_dia', 8, 2)->nullable();
            $table->decimal('precio_semana', 8, 2)->nullable();
            $table->decimal('precio_mes', 8, 2)->nullable();
            $table->string('moneda', 5)->default('PEN');
            $table->boolean('estado')->default(1);
            $table->timestamps();

            
            $table->foreign('COC_Id')->references('id')->on('cocheras')->onDelete('cascade');
            $table->foreign('TIV_Id')->references('id')->on('tipos_vehiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};
