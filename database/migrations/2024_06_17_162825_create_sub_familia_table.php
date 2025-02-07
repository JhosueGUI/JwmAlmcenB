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
        Schema::create('sub_familia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->foreignId('familia_id')->references('id')->on('familia')->onUpdate('cascade')->onDelete('cascade');
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_familia');
    }
};
