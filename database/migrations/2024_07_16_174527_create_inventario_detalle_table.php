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
        Schema::create('inventario_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->references('id')->on('inventario')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('demanda_media_mensual')->nullable();
            $table->string('demanda_minima_mensual')->nullable();
            $table->string('demanda_maxima_mensual')->nullable();
            $table->string('demanda_media_diaria')->nullable();
            $table->string('demanda_minima_diaria')->nullable();
            $table->string('demanda_maxima_diaria')->nullable();
            $table->string('lead_time')->nullable();
            $table->string('stock_minimo')->nullable();
            $table->string('stock_maximo')->nullable();
            $table->string('punto_pedido')->nullable();
            $table->string('lote_minimo')->nullable();
            $table->string('pedido_ajustado')->nullable();
            $table->string('valor_pedido_ajustado')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_detalle');
    }
};
