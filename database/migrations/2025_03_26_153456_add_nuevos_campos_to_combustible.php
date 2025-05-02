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
        Schema::table('combustible', function (Blueprint $table) {
            $table->foreignId('grifo_id')
                ->nullable()
                ->constrained('grifo')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('precio_unitario_igv')->nullable();
            $table->string('precio_total_igv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combustible', function (Blueprint $table) {
            $table->dropForeign(['grifo_id']);
            $table->dropColumn(['grifo_id', 'precio_unitario_igv', 'precio_total_igv']);
        });
    }
};