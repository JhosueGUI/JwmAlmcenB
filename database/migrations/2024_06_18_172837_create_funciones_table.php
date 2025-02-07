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
        Schema::create('funciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->references('id')->on('personal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('inventario_valorizado_id')->references('id')->on('inventario_valorizado')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funciones');
    }
};
