<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            $table->string('fecha_salida')->nullable();
            $table->string('inicio_contrato')->nullable();
            $table->string('fin_contrato')->nullable();
            $table->string('pdf_contrato')->nullable();
            $table->string('fecha_alta')->nullable();
            $table->string('fecha_baja')->nullable();
            $table->string('sueldo_planilla')->nullable();
            $table->string('sueldo_real')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_salida',
                'inicio_contrato',
                'fin_contrato',
                'pdf_contrato',
                'fecha_alta',
                'fecha_baja',
                'sueldo_planilla',
                'sueldo_real',
            ]);
        });
    }
};
