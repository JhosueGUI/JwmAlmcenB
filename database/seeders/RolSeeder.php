<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{

    public function run(): void
    {

        Rol::firstOrcreate([
            'nombre' => 'ADMINISTRADOR',
            'tipo_acceso'=>1
        ]);
        Rol::firstOrcreate([
            'nombre' => 'GERENTE',
            'tipo_acceso'=>2
        ]);
        Rol::firstOrcreate([
            'nombre' => 'PERSONAL',
            'tipo_acceso'=>3
        ]);
    }
}
