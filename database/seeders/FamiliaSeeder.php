<?php

namespace Database\Seeders;

use App\Models\Familia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamiliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familias=[
            'BEBIDAS',
            'COMUNICACIÓN',
            'HERRAMIENTAS',
            'IMPLEMENTOS',
            'JARDINERÍA',
            'LIMPIEZA',
            'MANTENIMIENTO',
            'MTTO CORRECTIVO',
            'MTTO PREVENTIVO',
            'NEUMATICOS',
            'SEGURIDAD',
            'ÚTILES DE OFICINA',
            'COMBUSTIBLE'
        ];
        foreach($familias as $familia){
            Familia::firstOrcreate([
                'familia'=>$familia
            ]);
        }
    }
}