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
        Schema::create('movimiento', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('n_operacion')->nullable();
            $table->string('ingreso')->nullable();
            $table->string('egreso')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('solicitante')->nullable();
            $table->string('sub_destino_placa')->nullable();
            $table->string('serie')->nullable();
            $table->string('n_factura')->nullable();
            $table->string('fecha_factura')->nullable();
            $table->string('obs')->nullable();
            $table->string('n_retencion')->nullable();
            $table->string('fecha_retencion')->nullable();
            $table->foreignId('modo_id')->nullable()->references('id')->on('modo')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->references('id')->on('cliente')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('persona_id')->nullable()->references('id')->on('persona')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->references('id')->on('proveedor')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sub_categoria_id')->nullable()->references('id')->on('sub_categoria')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('empresa_id')->nullable()->references('id')->on('empresa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('estado_id')->nullable()->references('id')->on('estado_comprobante')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('rendicion_id')->nullable()->references('id')->on('rendicion')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sustento_id')->nullable()->references('id')->on('sustento')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('moneda_id')->nullable()->references('id')->on('moneda')->onUpdate('cascade')->onDelete('cascade');
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento');
    }
};
