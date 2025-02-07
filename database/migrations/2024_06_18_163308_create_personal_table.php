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
        Schema::create('personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->nullable()->references('id')->on('persona')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('area_id')->references('id')->on('area')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('habilidad')->nullable();
            $table->string('experiencia')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
