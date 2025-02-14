<?php

namespace App\Imports;

use App\Models\Articulo;
use App\Models\Combustible;
use App\Models\DestinoCombustible;
use App\Models\Flota;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
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
                $destino = $row[13] ?? null;
                $contometro = $row[14] ?? null;
                $margen_error = $row[15] ?? null;
                $resultado = $row[16] ?? null;
                $precinto_nuevo = $row[17] ?? null;
                $precinto_anterior = $row[18] ?? null;
                $observaciones = $row[19] ?? null;

                //TRAEMOS A LA FLOTA
                $flota = Flota::where('estado_registro', 'A')->where('placa', $placa)->first();
                //TRAEMOS AL PRODUCTO
                $producto = Producto::where('estado_registro', 'A')->where('SKU', 537)->first();
                $articulo = $producto ? Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first() : null;

                //TRAEMOS AL PERSONAL
                $nombre_completo = null;
                $apellido = null;

                if ($personal) {
                    $nombre_partes = explode(' ', $personal);
                    $nombre_completo = implode(' ', array_slice($nombre_partes, 0, -1));
                    $apellido = end($nombre_partes);
                }
                
                $persona = $nombre_completo && $apellido 
                    ? Persona::where('estado_registro', 'A')
                        ->where('nombre', $nombre_completo)
                        ->where('apellido_paterno', $apellido)
                        ->first() 
                    : null;

                if (!$persona) {
                    Log::warning('Persona no encontrada en la fila: ' . json_encode($row));
                    continue;
                }

                $personal_id = Personal::where('estado_registro', 'A')
                    ->where('persona_id', $persona->id)
                    ->first();

                //TRAEMOS AL DESTINO
                $destino = DestinoCombustible::where('estado_registro', 'A')->where('nombre', $destino)->first();
                if (!$destino) {
                    Log::warning('Destino no encontrado en la fila: ' . json_encode($row));
                    continue;
                }

                //CREAMOS LA TRANSACCIÓN
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

                if ($salida_combustible_stock !== null && $salida_combustible_stock != 0) {
                    //TRAEMOS EL INVENTARIO
                    $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();

                    //SUMAMOS EL TOTAL DE SALIDA
                    $t_salida = $inventario->total_salida;
                    $total_salida = $salida_combustible_stock + $t_salida;

                    //CALCULAMOS EL STOCK LÓGICO
                    $total_ingreso = $inventario->total_ingreso;
                    $stock_logico = $total_ingreso - $total_salida;

                    //ACTUALIZAMOS EL INVENTARIO
                    $inventario->update([
                        'total_salida' => $total_salida,
                        'stock_logico' => $stock_logico,
                    ]);

                    //TRAEMOS EL INVENTARIO VALORIZADO
                    $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')
                        ->where('inventario_id', $inventario->id)
                        ->first();

                    //CALCULAMOS EL VALOR DEL INVENTARIO
                    $valor_inventario_soles = $stock_logico * $articulo->precio_soles;

                    //ACTUALIZAMOS EL INVENTARIO VALORIZADO
                    $inventario_valorizado->update([
                        'valor_inventario_soles' => $valor_inventario_soles,
                    ]);

                    //CREAMOS EL REGISTRO DE COMBUSTIBLE
                    Combustible::create([
                        'fecha' => $fecha,
                        'destino_combustible_id' => $destino->id,
                        'personal_id' => $personal_id->id,
                        'flota_id' => $flota->id,
                        'transaccion_id' => $transaccion->id,
                        'numero_salida_stock' => $salida_combustible_stock,
                        'precio_unitario_soles' => $transaccion->precio_unitario_soles,
                        'precio_total_soles' => $transaccion->precio_total_soles,
                        'contometro_surtidor_inicial' => 26487,
                        'contometro_surtidor' => $contometro,
                        'margen_error_surtidor' => $margen_error,
                        'resultado' => $resultado,
                        'precinto_nuevo' => $precinto_nuevo,
                        'precinto_anterior' => $precinto_anterior,
                        'kilometraje' => $kilometraje,
                        'horometro' => $horometro,
                        'observacion' => $observaciones,
                    ]);
                } elseif ($salida_combustible_ruta !== null && $salida_combustible_ruta != 0) {
                    Combustible::create([
                        'fecha' => $fecha,
                        'destino_combustible_id' => $destino->id,
                        'personal_id' => $personal_id->id,
                        'flota_id' => $flota->id,
                        'numero_salida_ruta' => $salida_combustible_ruta,
                        'precio_unitario_soles' => $precio_unitario_soles,
                        'precio_total_soles' => $precio_total_soles,
                        'precinto_nuevo' => $precinto_nuevo,
                        'precinto_anterior' => $precinto_anterior,
                        'kilometraje' => $kilometraje,
                        'horometro' => $horometro,
                        'observacion' => $observaciones,
                    ]);
                }
            }
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
