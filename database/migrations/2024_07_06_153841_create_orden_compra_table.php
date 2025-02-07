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
        Schema::create('orden_compra', function (Blueprint $table) {
            $table->id();
            $table->string('fecha')->nullable();
            $table->string('numero_orden')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('IGV')->nullable();
            $table->string('total')->nullable();
            $table->string('url_pdf')->nullable();
            $table->foreignId('proveedor_id')->nullable()->references('id')->on('proveedor')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('requerimiento')->nullable();
            $table->string('gestor_compra')->nullable();
            $table->string('solicitante')->nullable();
            $table->string('detalle')->nullable();
            $table->string('cotizacion')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compra');
    }
};
