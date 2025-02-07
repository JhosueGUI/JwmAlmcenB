<?php

namespace Database\Seeders;

use App\Models\DestinoCombustible;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinoCombustibleSeeder extends Seeder
{
    public function run(): void
    {
        $destinos=[
            "INVERSIONES & SERVICIOS JWM SAC",
            "TALLER",
            "PURGAR",
            "TERCERO",
            "VENTA"
        ];
        foreach($destinos as $destino){
            DestinoCombustible::create([
                'nombre'=>$destino,
                'estado_registro'=>'A'
            ]);
        }
    }
}
