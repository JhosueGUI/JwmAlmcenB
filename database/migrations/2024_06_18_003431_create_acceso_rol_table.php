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
        Schema::create('acceso_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acceso_id')->references('id')->on('acceso')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('rol_id')->references('id')->on('rol')->cascadeOnDelete()->cascadeOnUpdate();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceso_rol');
    }
};
