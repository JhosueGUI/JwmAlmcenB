<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\Sustento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SustentoSeeder extends Seeder
{
    public function run(): void
    {
        $sustentos = [
            'CON SUSTENTO',
            'SIN SUSTENTO',
        ];
        foreach ($sustentos as $sustento) {
            Sustento::create([
                'nombre_sustento' => $sustento,
            ]);
        }
    }
}
