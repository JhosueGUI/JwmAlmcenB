<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            'TRANSF.CAJ.HUANCAYO',
            'TRAN.CTAS.TERC.BM',
            'VARIOS SAINT-GOBAIN PR',
            'DE ADUAMERICA SA'
        ];
        foreach ($clientes as $cliente) {
            Cliente::create([
                'nombre_cliente' => $cliente,
            ]);
        }
    }
}
