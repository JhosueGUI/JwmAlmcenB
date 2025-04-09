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
        Schema::create('formulario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->nullable()->references('id')->on('personal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('titulo')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('url_pdf')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario');
    }
};
