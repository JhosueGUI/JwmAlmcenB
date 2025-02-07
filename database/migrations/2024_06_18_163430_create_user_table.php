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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->references('id')->on('personal')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
