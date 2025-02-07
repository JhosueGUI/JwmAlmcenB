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
        Schema::create('flota', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->nullable();
            $table->foreignId('personal_id')->nullable()->references('id')->on('personal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tipo')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('empresa')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flota');
    }
};
