<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moneda', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_moneda')->nullable();
            $table->char('estado_registro')->default('A');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('moneda');
    }
};
