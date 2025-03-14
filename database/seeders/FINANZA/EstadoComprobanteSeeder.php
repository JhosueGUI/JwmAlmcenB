<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\EstadoComprobante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoComprobanteSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'ENTREGADO',
            'PENDIENTE'
        ];
        foreach ($estados as $estado) {
            EstadoComprobante::create([
                'nombre_estado_comprobante' => $estado,
            ]);
        }
    }
}
