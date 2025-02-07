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
        Schema::create('ingreso', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('guia_remision')->nullable();
            $table->string('tipo_cp')->nullable();
            $table->string('documento')->nullable();
            $table->string('orden_compra')->nullable();
            $table->string('numero_ingreso')->nullable();
            $table->foreignId('transaccion_id')->references('id')->on('transaccion')->cascadeOnDelete()->cascadeOnUpdate();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso');
    }
};
