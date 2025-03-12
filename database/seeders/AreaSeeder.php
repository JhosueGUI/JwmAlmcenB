<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas=[
            'ADMINISTRACIÓN Y FINANZAS',
            'OPERACIONES',
            'COMERCIAL',
            'VIGILANCIA',
            'LOGISTICA',
            'ALMACEN',
            'FLOTA',
            'LIMPIEZA',
            'GERENCIA GENERAL'
        ];
        foreach ($areas as $area ) {
            Area::firstOrcreate([
                'nombre'=>$area,
                'horario_id'=>1
            ]);
        }
    }
}