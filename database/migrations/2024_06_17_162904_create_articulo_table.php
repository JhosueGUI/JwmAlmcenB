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
        Schema::create('articulo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('precio_soles')->nullable();
            $table->string('precio_dolares')->nullable();
            $table->foreignId('sub_familia_id')->nullable()->references('id')->on('sub_familia')->onDelete('cascade')->onUpdate('cascade');
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo');
    }
};
