<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\Modo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modos=[
            'BANCA MOVIL',
            'TELECREDITO',
        ];
        foreach ($modos as $modo) {
            Modo::create([
                'nombre_modo' => $modo
            ]);
        }
    }
}
