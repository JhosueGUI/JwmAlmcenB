<?php

namespace App\Imports;

use App\Models\Flota;
use App\Models\Persona;
use App\Models\Personal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FlotaImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $columnas)
    {
        DB::transaction(function () use ($columnas) {
            // Obtener flotas existentes para evitar duplicados
            $FlotaExistente = Flota::where('estado_registro', 'A')->get()
                ->keyBy(function ($item) {
                    return $item->placa . $item->personal_id;
                });

            $contador = 0;
            foreach ($columnas as $columna) {
                $contador++;
                if ($contador === 1) {
                    continue; // Skip header row
                }

                // Verificar que la columna tiene al menos 7 elementos
                if (count($columna) < 4) {
                    Log::warning('Fila con datos incompletos: ' . json_encode($columna));
                    continue; // Saltar filas incompletas
                }

                $placa = $columna[0] ?? null;
                $conductorNombre = explode(' ', $columna[1] ?? '');
                $tipo = $columna[2] ?? null;
                $marca = $columna[3] ?? null;
                $modelo = $columna[4] ?? null;
                $empresa = $columna[5] ?? null;

                // Verificar que el nombre y apellido del conductor no estén vacíos
                if (count($conductorNombre) < 2) {
                    Log::warning('Nombre completo del conductor incompleto: ' . json_encode($columna[1]));
                    continue; // Saltar filas con nombre de conductor incompleto
                }

                // Buscar persona por nombre y apellido
                $persona = Persona::where('nombre', $conductorNombre[0])
                                  ->where('apellido_paterno', $conductorNombre[1])
                                  ->first();
                
                // Buscar conductor basado en la persona
                $conductor = $persona ? Personal::where('persona_id', $persona->id)->first() : null;

                // Crear flota si no existe en la base de datos
                if (!$FlotaExistente->has($placa . ($conductor ? $conductor->id : 'null'))) {
                    Flota::create([
                        'placa' => $placa,
                        'personal_id' => $conductor ? $conductor->id : null,
                        'tipo' => $tipo,
                        'marca' => $marca,
                        'modelo' => $modelo,
                        'empresa' => $empresa,
                    ]);
                }
            }
        });
    }
}
