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
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->references('id')->on('producto')->onDelete('cascade')->onUpdate('cascade');
            // $table->string('valor_inventario')->nullable();
            $table->string('total_ingreso')->nullable();
            $table->string('total_salida')->nullable();
            $table->string('stock_logico')->nullable();
            $table->string('demanda_mensual')->nullable();
            $table->foreignId('estado_operativo_id')->nullable()->references('id')->on('estado_operativo')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ubicacion_id')->nullable()->references('id')->on('ubicacion')->cascadeOnDelete()->cascadeOnUpdate();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
