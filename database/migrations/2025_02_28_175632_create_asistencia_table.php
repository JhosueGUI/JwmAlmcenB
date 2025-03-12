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
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->nullable()->references('id')->on('persona')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('fecha_asistencia')->nullable();
            $table->string('dia_asistencia')->nullable();
            $table->string('hora_ingreso')->nullable();
            $table->string('hora_salida')->nullable();
            $table->string('tiempo_total')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};
