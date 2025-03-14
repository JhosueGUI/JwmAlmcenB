<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias=[
            'INGRESO DE SERVICIOS',
            'INGRESO DE FINANCIAMIENTO',
            'INGRESO NO OPERACIONALES',
            'COSTOS DE SERVICIO VARIABLE',
            'GASTO DE SERVICIO VARIABLE',
            'COSTOS FIJOS',
            'GASTOS FIJOS',
            'GASTOS OPERACIONALES ',
            'GASTOS DE MTTO Y REPARACION',
            'GASTOS ADMINISTRATIVOS',
            'GASTOS FINANCIEROS PRESTAMOS',
            'INVERSIONES',
            'GASTOS NO OPERACIONALES',
            'SALIDAS NO RELACIONADAS Y BANCARIZACIONES'
        ];
        foreach ($categorias as $categoria) {
            Categoria::create([
                'nombre_categoria' => $categoria
            ]);
        }
    }
}
