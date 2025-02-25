<?php
namespace Database\Seeders;

use App\Models\Acceso;
use App\Models\AccesoRol;
use App\Models\Area;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\User;
use App\Models\UsuarioRol;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $persona = Persona::updateOrCreate(
            ["numero_documento" => 12082024], // Condición de búsqueda
            ["nombre" => "admin", "tipo_documento_id" => 1] // Datos a actualizar si ya existe
        );

        $personal = Personal::updateOrCreate(
            ["persona_id" => $persona->id], 
            ["area_id" => 1]
        );

        $user = User::updateOrCreate(
            ["username" => "SuperAdmin"],
            ["personal_id" => $personal->id, "password" => bcrypt($persona->numero_documento)]
        );

        UsuarioRol::updateOrCreate(
            ["rol_id" => 1, "user_id" => $user->id]
        );

        $accesos = Acceso::where('estado_registro', 'A')->get();
        foreach ($accesos as $acceso) {
            AccesoRol::updateOrCreate(
                ["rol_id" => 1, "acceso_id" => $acceso->id]
            );
        }
    }
}

