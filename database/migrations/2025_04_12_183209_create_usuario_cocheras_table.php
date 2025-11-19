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
        Schema::create('usuario_cocheras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('COC_Id');
            $table->unsignedBigInteger('USU_Id');
            $table->timestamps();

            $table->foreign('COC_Id')->references('id')->on('cocheras')->onDelete('cascade');
            $table->foreign('USU_Id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_cocheras');
    }
};
