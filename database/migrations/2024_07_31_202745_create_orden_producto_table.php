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
        Schema::create('orden_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->references('id')->on('orden_compra')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->references('id')->on('producto')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('cantidad')->nullable();
            $table->string('precio_soles')->nullable();
            $table->string('precio_dolares')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_producto');
    }
};
