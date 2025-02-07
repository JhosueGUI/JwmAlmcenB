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
        Schema::create('inventario_valorizado', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('transaccion_id')->references('id')->on('transaccion')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('inventario_id')->references('id')->on('inventario')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('valor_unitario_soles')->nullable();
            $table->string('valor_unitario_dolares')->nullable();
            $table->string('valor_inventario_soles')->nullable();
            $table->string('valor_inventario_dolares')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_valorizado');
    }
};
