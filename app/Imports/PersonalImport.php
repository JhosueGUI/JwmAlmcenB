<?php

namespace App\Imports;

use App\Models\Personal;
use App\Models\Persona;
use App\Models\RRHH\Cargo;
use App\Models\RRHH\Planilla;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PersonalImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Obtener todos los registros de Personal y Personas para comparación
        $existingPersonals = Personal::with('persona', 'cargo')
            ->get()
            ->keyBy(function ($item) {
                return $item->persona->nombre . $item->persona->apellido_paterno . $item->cargo->id;
            });

        $rowNumber = 0;

        DB::transaction(function () use ($rows, $existingPersonals, &$rowNumber) {
            foreach ($rows as $row) {
                $rowNumber++;

                if ($rowNumber === 1) {
                    continue;
                }


                $nombre = $row[0];
                $apellido_p = $row[1];
                $apellido_m = $row[2];
                $fecha_nacimiento = date('Y-m-d', strtotime($row[3]));
                $gmail = $row[4];
                $numero_d = $row[6];
                $fecha_ingreso = date('Y-m-d', strtotime($row[7]));
                $cargo_nombre = $row[9];
                $fecha_planilla = date('Y-m-d', strtotime($row[10]));
                $planilla_nombre = $row[11];
                $habilidad = $row[12];
                $experiencia = $row[13];



                $cargo = Cargo::where('estado_registro', 'A')
                    ->where('nombre_cargo', $cargo_nombre)
                    ->first();
                $planilla = Planilla::where('nombre_planilla', $planilla_nombre)->first();

                if ($cargo === null) {
                    // Si no se encuentra el cargo, podrías registrar un error o continuar
                    continue;
                }
 
                // Verificar si el registro ya existe en la colección de registros existentes
                $key = $nombre . $apellido_p . $cargo->id;
                if (!$existingPersonals->has($key)) {
                    // Si no existe, crear la persona y el registro de Personal
                    $persona = Persona::firstOrCreate([
                        'nombre' => $nombre,
                        'apellido_paterno' => $apellido_p,
                        'apellido_materno' => $apellido_m,
                        'gmail' => $gmail,
                        'tipo_documento_id' => 1,
                        'numero_documento' => $numero_d,
                        'fecha_nacimiento' => $fecha_nacimiento
                    ]);

                    Personal::create([
                        'persona_id' => $persona->id,
                        'cargo_id' => $cargo->id,
                        'habilidad' => $habilidad,
                        'experiencia' => $experiencia,
                        'fecha_ingreso' => $fecha_ingreso,
                        'planilla_id' => $planilla ? $planilla->id : null, 
                        'fecha_ingreso_planilla' => $fecha_planilla,
                    ]);
                }
            }
        });
    }
}
