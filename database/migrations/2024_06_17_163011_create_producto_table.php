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
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('SKU')->nullable();
            // $table->string('marca')->nullable();
            $table->foreignId('articulo_id')->nullable()->references('id')->on('articulo')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('unidad_medida_id')->nullable()->references('id')->on('unidad_medida')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('proveedor_id')->nullable()->references('id')->on('proveedor')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('ubicacion_id')->references('id')->on('ubicacion')->onDelete('cascade')->onDelete('cascade');
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
