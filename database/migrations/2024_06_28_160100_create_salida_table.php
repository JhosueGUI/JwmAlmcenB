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
        Schema::create('salida', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('vale')->nullable();
            $table->string('destino')->nullable();
            $table->foreignId('personal_id')->nullable()->references('id')->on('personal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('unidad')->nullable();
            $table->string('duracion_neumatico')->nullable();
            $table->string('kilometraje_horometro')->nullable();
            $table->string('fecha_vencimiento')->nullable();
            $table->string('numero_salida')->nullable();
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
        Schema::dropIfExists('salida');
    }
};
