<?php

namespace Database\Seeders\RRHH;

use App\Models\RRHH\Planilla;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanillaSeeder extends Seeder
{
    public function run(): void
    {
        $planillas = [
            "FSJ OPERADOR LOGISTICO S.A.C",
            "GRUPO JWM S.A.C.",
            "HACIENDA PAMPAYA S.A.C.",
            "INVERSIONES & SERVICIOS JWM S.A.C.",
            "SERVICENTRO EL CAMIONERO S.A.C.",
            "NINGUNA",
        ];
        foreach ($planillas as $planilla) {
            Planilla::create([
                'nombre_planilla' => $planilla,
            ]);
        }
    }
}
