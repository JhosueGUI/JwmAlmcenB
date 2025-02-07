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
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social')->nullable();
            $table->string('ruc')->nullable();
            $table->string('direccion')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('contacto')->nullable();
            // $table->foreignId('persona_id')->references('id')->on('persona')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('numero_celular')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedor');
    }
};
