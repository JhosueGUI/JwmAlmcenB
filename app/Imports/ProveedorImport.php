<?php

namespace App\Imports;

use App\Models\Proveedor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class ProveedorImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $columnas)
    {
        DB::transaction(function () use ($columnas) {
            // Obtener todos los registros de Proveedor para comparación
            $ProvedoresExistentes = Proveedor::where('estado_registro', 'A')
                ->get()
                ->keyBy(function ($item) {
                    return $item->ruc;
                });

            $contador = 0;
            foreach ($columnas as $columna) {
                $contador++;
                if ($contador === 1) {
                    continue; // Omitir la fila de encabezado
                }

                // Verificar que la columna tiene al menos 7 elementos
                if (count($columna) < 5) {
                    Log::warning('Fila con datos incompletos: ' . json_encode($columna));
                    continue; // Saltar filas incompletas
                }

                $razon_social = $columna[0] ?? null;
                $ruc = $columna[1] ?? null;
                $direccion = $columna[2] ?? null;
                $forma_pago = $columna[3] ?? null;
                $contacto = implode(' ', explode(' ', $columna[4] ?? ''));
                $numero_celular = implode(' ', explode(' ', $columna[5] ?? ''));

                // Verificar si el registro ya existe en la colección de registros existentes
                if (!$ProvedoresExistentes->has($ruc)) {
                    Proveedor::create([
                        'razon_social' => $razon_social,
                        'ruc' => $ruc,
                        'direccion' => $direccion,
                        'forma_pago' => $forma_pago,
                        'contacto' => $contacto,
                        'numero_celular' => $numero_celular,
                        'estado_registro' => 'A', // Asegúrate de establecer un valor por defecto para estado_registro
                    ]);
                }
            }
        });
    }
}
