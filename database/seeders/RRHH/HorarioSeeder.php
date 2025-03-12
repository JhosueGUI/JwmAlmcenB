<?php

namespace Database\Seeders\RRHH;

use App\Models\RRHH\Horario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $horarios = [
            '08:00 - 18:00',
        ];
        foreach ($horarios as $horario) {
            Horario::create([
                'horario_estandar' => $horario,
            ]);
        }
    }
}
