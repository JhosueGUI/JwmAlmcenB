<?php

namespace App\Imports;

use App\Models\Articulo;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Transaccion;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalidaCombustibleImport implements ToArray, WithChunkReading
{
    public function array(array $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $placa = $row[0] ?? null;
                $fecha = date('Y-m-d', strtotime($row[1]));
                $kilometraje = $row[2] ?? null;
                $horometro = $row[3] ?? null;
                $personal = $row[4] ?? null;
                $salida_combustible_stock = $row[5] ?? null;
                $salida_combustible_ruta = $row[6] ?? null;
                $precio_unitario_soles = $row[7] ?? null;
                $precio_total_soles = $row[8] ?? null;

                //dividir el nombre completo en nombre y apellido
                $nombre_completo = null;
                $apellido = null;

                if ($personal) {
                    $nombre_completo = implode(' ', array_slice(explode(' ', $personal), 0, -1));
                    $apellido = end($personal);
                }
                $persona = $nombre_completo && $apellido ? Persona::where('estado_registro', 'A')->where('nombre', $nombre_completo)->where('apellido_paterno', $apellido)->first() : null;
                if (!$persona) {
                    Log::warning('Persona no encontrada en la fila: ' . json_encode($row));
                    continue;
                }
                $personal_id = $persona ? Personal::where('estado_registro', 'A')->where('persona_id', $persona->id)->first() : null;

                //traemos el producto
                $producto = Producto::where('estado_registro', 'A')->where('SKU', 537)->first();
                $articulo_id = $producto ? Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first() : null;

                //creamos la transaccion
                $transaccion = Transaccion::create([
                    'producto_id' => $producto->id,
                    'tipo_operacion' => null,
                    'precio_unitario_soles' => $precio_unitario_soles,
                    'precio_unitario_dolares' => null,
                    'precio_total_soles' => $precio_total_soles,
                    'precio_total_dolares' => null,
                    'marca' => null,
                    'observaciones' => null,
                ]);
            }
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
