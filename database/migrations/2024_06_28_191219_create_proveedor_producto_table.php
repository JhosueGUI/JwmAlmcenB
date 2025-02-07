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
        Schema::create('proveedor_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->references('id')->on('proveedor')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('producto_id')->references('id')->on('producto')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('identificador')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedor_producto');
    }
};
