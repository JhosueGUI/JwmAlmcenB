<?php

namespace Database\Seeders;

use App\Models\Acceso;
use App\Models\AccesoRol;
use App\Models\Area;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\User;
use App\Models\UsuarioRol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $persona=Persona::firstOrcreate([
            "nombre"=>"admin",
            "tipo_documento_id"=>1,
            "numero_documento"=>12082024
        ]);
        $personal=Personal::firstOrcreate([
            "persona_id"=>$persona->id,
            "area_id"=>1
        ]);
        $user=User::firstOrcreate([
            "personal_id"=>$personal->id,
            "username"=>"SuperAdmin",
            "password"=>$persona->numero_documento
        ]);
        $user_rol=UsuarioRol::firstOrcreate([
            "rol_id"=>1,
            "user_id"=>$user->id
        ]);
        $accesos=Acceso::where('estado_registro','A')->get();
        foreach($accesos as $acceso){
            AccesoRol::firstOrcreate([
                "rol_id"=>1,
                "acceso_id"=>$acceso->id
            ]);
        }
    }
}
