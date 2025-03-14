<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [
            'CAJA CAMIONERO',
            'CAJA FSJ',
            'CAJA PAMPAYA',
            'CAJA WILLIAM',
            'CAJA JOEL',
        ];
        foreach ($empresas as $empresa) {
            Empresa::create([
                'nombre_empresa' => $empresa,
            ]);
        }
    }
}
