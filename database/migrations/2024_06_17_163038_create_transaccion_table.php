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
        Schema::create('transaccion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->references('id')->on('producto')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tipo_operacion')->nullable();
            $table->string('precio_unitario_soles')->nullable();
            $table->string('precio_total_soles')->nullable();
            $table->string('precio_unitario_dolares')->nullable();
            $table->string('precio_total_dolares')->nullable();
            $table->string('marca')->nullable();
            $table->string('observaciones')->nullable();
            // $table->string('fecha_ingreso')->nullable();
            // $table->string('fecha_salida')->nullable();
            // $table->string('precio_dolares')->nullable();
            // $table->string('precio_soles')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccion');
    }
};
