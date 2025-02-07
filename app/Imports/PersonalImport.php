<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Personal;
use App\Models\Persona;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class PersonalImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Obtener todos los registros de Personal y Personas para comparación
        $existingPersonals = Personal::with('persona', 'area')
            ->get()
            ->keyBy(function ($item) {
                return $item->persona->nombre . $item->persona->apellido_paterno . $item->area->id;
            });

        $rowNumber = 0;

        DB::transaction(function () use ($rows, $existingPersonals, &$rowNumber) {
            foreach ($rows as $row) {
                $rowNumber++;

                // Omitir la primera fila (encabezados)
                if ($rowNumber === 1) {
                    continue;
                }

                $nombre = $row[0];
                $apellido_p = $row[1];
                $apellido_m = $row[2];
                $gmail=$row[3];
                $numero_d = $row[5];
                $area_nombre = $row[6];
                $habilidad=$row[7];
                $experiencia=$row[8];


                $area = Area::where('estado_registro', 'A')
                    ->where('nombre', $area_nombre)
                    ->first();

                if ($area === null) {
                    // Si no se encuentra el área, podrías registrar un error o continuar
                    continue;
                }

                // Verificar si el registro ya existe en la colección de registros existentes
                $key = $nombre . $apellido_p . $area->id;
                if (!$existingPersonals->has($key)) {
                    // Si no existe, crear la persona y el registro de Personal
                    $persona = Persona::firstOrCreate([
                        'nombre' => $nombre,
                        'apellido_paterno' => $apellido_p,
                        'apellido_materno' => $apellido_m,
                        'numero_documento' => $numero_d,
                        'gmail'=>$gmail,
                        'tipo_documento_id' => 1
                    ]);

                    Personal::create([
                        'persona_id' => $persona->id,
                        'area_id' => $area->id,
                        'habilidad'=>$habilidad,
                        'experiencia'=>$experiencia
                    ]);
                }
            }
        });
    }
}
