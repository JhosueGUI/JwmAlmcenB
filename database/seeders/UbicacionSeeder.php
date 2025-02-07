<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    public function run(): void
    {
        $ubicaciones=[
            '1-E1-1',
            '1-E1-2',
            '1-E1-3',
            '1-E1-4',
            '1-E1-5',
            '1-E2-1',
            '1-E2-2',
            '1-E2-3',
            '1-E2-4',
            '1-E2-5',
            '1-E3-1',
            '1-E3-2',
            '1-E3-3',
            '1-E3-4',
            '1-E3-5',
            '1-E4-1',
            '1-E4-2',
            '1-E4-3',
            '1-E4-4',
            '1-E4-5',
            '1-E5-1',
            '1-E5-2',
            '1-E5-3',
            '1-E5-4',
            '1-E5-5',
            '1-E6-1',
            '1-E6-2',
            '1-E6-3',
            '1-E6-4',
            '1-E6-5',
            '1-E7-1',
            '1-E7-2',
            '1-E7-3',
            '1-E7-4',
            '1-E7-5',
            '1-E8-1',
            '1-E8-2',
            '1-E8-3',
            '1-E8-4',
            '1-E8-5',
            '1-E9-1',
            '1-E9-2',
            '1-E9-3',
            '1-E9-4',
            '1-E9-5',
            '1-E10-1',
            '1-E10-2',
            '1-E10-3',
            '1-E10-4',
            '1-E10-5',
            '1-E11-1',
            '1-E11-2',
            '1-E11-3',
            '1-E11-4',
            '1-E11-5',
            '1-E12-1',
            '1-E12-2',
            '1-E12-3',
            '1-E12-4',
            '1-E12-5',
            '1-E13-1',
            '1-E13-2',
            '1-E13-3',
            '1-E13-4',
            '1-E13-5',
            '1-E13-6',
            '1-E13-6',
            'ESTANTE',
            'PASILLO',
            'ALMACEN SUPERIOR',
            'COFRE'

        ];
        foreach($ubicaciones as $ubicacion){
            Ubicacion::firstOrcreate([
                'codigo_ubicacion'=>$ubicacion
            ]);
        }
    }
}