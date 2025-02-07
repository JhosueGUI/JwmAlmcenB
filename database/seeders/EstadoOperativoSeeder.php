<?php

namespace Database\Seeders;

use App\Models\EstadoOperativo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoOperativoSeeder extends Seeder
{
    public function run(): void
    {
        $estadoOperativos=[
            'F',
            'INMOVILIZADO',
            'OPERATIVO'
        ];
        foreach($estadoOperativos as $estadoOperativo){
            EstadoOperativo::firstOrcreate([
                'nombre'=>$estadoOperativo
            ]);
        }
    }
}
