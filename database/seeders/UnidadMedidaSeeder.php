<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $unidadMedidas=[
            'BAL',
            'BIDÓN',
            'BLS',
            'CJA',
            'GL',
            'KIT',
            'MTS',
            'PAR',
            'PQT',
            'RLL',
            'ROLL',
            'SACO',
            'UND',
            'GAL',
            'JGO',
            'SET'
            
        ];
        foreach($unidadMedidas as $unidadMedida){
            UnidadMedida::firstOrcreate([
                'nombre'=>$unidadMedida
            ]);
        }
    }
}