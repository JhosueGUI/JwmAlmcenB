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
        Schema::create('combustible', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->foreignId('destino_combustible_id')->nullable()->references('id')->on('destino_combustible')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('personal_id')->nullable()->references('id')->on('personal')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('flota_id')->nullable()->references('id')->on('flota')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('transaccion_id')->nullable()->references('id')->on('transaccion')->onUpdate('cascade')->onDelete('cascade');
            $table->string('numero_salida_stock')->nullable();
            $table->string('numero_salida_ruta')->nullable();
            //añadir
            $table->string('tipo_comprobante')->nullable();
            $table->string('numero_comprobante')->nullable();
            
            $table->string('precio_unitario_soles')->nullable();
            $table->string('precio_total_soles')->nullable();
            //
            $table->string('contometro_surtidor_inicial')->nullable();
            $table->string('contometro_surtidor')->nullable();
            $table->string('margen_error_surtidor')->nullable();
            $table->string('resultado')->nullable();
            $table->string('precinto_nuevo')->nullable();
            $table->string('precinto_anterior')->nullable();
            $table->string('kilometraje')->nullable();
            $table->string('horometro')->nullable();
            $table->string('observacion')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combustible');
    }
};
