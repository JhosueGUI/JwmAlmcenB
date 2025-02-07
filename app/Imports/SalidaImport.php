<?php

namespace App\Imports;

use App\Models\Articulo;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Salida;
use App\Models\Transaccion;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SalidaImport implements ToArray, WithChunkReading
{
    /**
     * Maneja la importación de datos desde una colección de Excel.
     *
     * @param array $rows La colección de datos de la hoja de Excel en formato array.
     */
    public function array(array $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                if ($index === 0 || count($row) < 6) {
                    Log::warning('Fila con datos incompletos o de encabezado: ' . json_encode($row));
                    continue;
                }

                // Extrae y convierte los valores de cada columna.
                $fecha = date('Y-m-d', strtotime($row[0]));
                
                $vale = $row[1] ?? null;
                $tipo_operacion = $row[2] ?? null;
                $destino = $row[3] ?? null;
                $personal_nombre = isset($row[4]) ? explode(' ', $row[4]) : null;
                $unidad = $row[5] ?? null;
                $duracion_neumatico = $row[6] ?? null;
                $kilometraje_horometro = $row[7] ?? null;
                $fecha_vencimiento = $row[8] ?? null;
                $sku = $row[9] ?? null;
                $marca = $row[13] ?? null;
                $numero_salida = floatval($row[15] ?? 0); // Convertir a flotante
                $precio_unitario_soles = floatval($row[16] ?? 0); // Convertir a flotante
                $precio_unitario_dolares = floatval($row[17] ?? 0); // Convertir a flotante
                $observaciones = $row[19] ?? null;

                // Validaciones
                if ($numero_salida <= 0) {
                    Log::warning('Número de salida no válido en la fila: ' . json_encode($row));
                    continue;
                }

                if ($precio_unitario_soles < 0 || $precio_unitario_dolares < 0) {
                    Log::warning('Precio unitario negativo en la fila: ' . json_encode($row));
                    continue;
                }

                // Divide el nombre completo en nombre y apellidos si personal_nombre no es null
                $nombre_completo = null;
                $apellido = null;

                if ($personal_nombre) {
                    $nombre_completo = implode(' ', array_slice($personal_nombre, 0, -1)); // Nombre
                    $apellido = end($personal_nombre); // Último elemento como apellido
                }

                $producto = $sku ? Producto::where('estado_registro', 'A')->where('SKU', $sku)->first() : null;
                $persona = $nombre_completo && $apellido
                    ? Persona::where('estado_registro', 'A')->where('nombre', $nombre_completo)->where('apellido_paterno', $apellido)->first()
                    : null;

                $producto_id = ($producto && $sku !== "") ? $producto->id : null;
                $persona_id = ($persona && $nombre_completo !== "" && $apellido !== "") ? $persona->id : null;
                $articulo = $producto ? Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first() : null;
                if (!$producto) {
                    Log::warning('Producto no encontrado con SKU: ' . $sku);
                    continue;
                }

                if ($persona && !$persona->personal) {
                    Log::warning('Persona no asociada a un registro de personal: ' . json_encode($personal_nombre));
                    continue;
                }

                // Crear la transacción
                $transaccion = Transaccion::create([
                    'producto_id' => $producto_id,
                    'tipo_operacion' => $tipo_operacion,
                    'precio_unitario_soles' => $precio_unitario_soles,
                    'precio_total_soles' => $precio_unitario_soles * $numero_salida,
                    'precio_unitario_dolares' => $precio_unitario_dolares,
                    'precio_total_dolares' => $precio_unitario_dolares * $numero_salida,
                    'marca' => $marca,
                    'observaciones' => $observaciones
                ]);
                // Crear la salida
                $salida = Salida::create([
                    'fecha' => $fecha,
                    'vale' => $vale,
                    'destino' => $destino,
                    'unidad' => $unidad,
                    'duracion_neumatico' => $duracion_neumatico,
                    'kilometraje_horometro' => $kilometraje_horometro,
                    'fecha_vencimiento' => $fecha_vencimiento,
                    'numero_salida' => $numero_salida,
                    'personal_id' => $persona_id ?? null,
                    'transaccion_id' => $transaccion->id,
                ]);
                // Obtener el inventario
                $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();
                if (!$inventario) {
                    $inventario = Inventario::create(['producto_id' => $producto->id]);
                }
                $total_ingreso = $this->getNumerosIngresoProducto($producto->id);
                $total_salida = $this->getNumerosSalidaProducto($producto->id);
                $stock_logico = $total_ingreso - $total_salida;

                $inventario->update([
                    'total_salida' => $total_salida,
                    'stock_logico' => $stock_logico,
                ]);

                // Obtener o crear inventario valorizado
                $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();
                if (!$inventario_valorizado) {
                    $inventario_valorizado = InventarioValorizado::create(['inventario_id' => $inventario->id]);
                }
                $inventario_valorizado->update([
                    'valor_inventario_soles' => $stock_logico * $precio_unitario_soles,
                    'valor_inventario_dolares' => $stock_logico * $precio_unitario_dolares,
                ]);
            }
        });
    }

    /**
     * Configura el tamaño del chunk.
     */
    public function chunkSize(): int
    {
        return 1000; // Procesa 1000 filas por cada chunk
    }

    /**
     * Obtiene el total de números de ingreso para un producto.
     *
     * @param int $idProducto
     * @return float
     */
    private function getNumerosIngresoProducto($idProducto)
    {
        try {
            $producto = Producto::with('transaccion.ingreso')->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return 0.0; // Producto no encontrado, devolver 0.0 en lugar de respuesta JSON
            }

            $totalNumerosIngreso = 0.0;
            foreach ($producto->transaccion as $transaccion) {
                foreach ($transaccion->ingreso as $ingreso) {
                    $totalNumerosIngreso += floatval($ingreso->numero_ingreso);
                }
            }

            return $totalNumerosIngreso;
        } catch (\Exception $e) {
            Log::error('Error al obtener números de ingreso: ' . $e->getMessage());
            return 0.0; // En caso de error, devolver 0.0
        }
    }

    /**
     * Obtiene el total de números de salida para un producto.
     *
     * @param int $idProducto
     * @return float
     */
    private function getNumerosSalidaProducto($idProducto)
    {
        try {
            $producto = Producto::with('transaccion.salida')->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return 0.0; // Producto no encontrado, devolver 0.0 en lugar de respuesta JSON
            }

            $totalNumerosSalida = 0.0;
            foreach ($producto->transaccion as $transaccion) {
                foreach ($transaccion->salida as $salida) {
                    $totalNumerosSalida += floatval($salida->numero_salida);
                }
            }

            return $totalNumerosSalida;
        } catch (\Exception $e) {
            Log::error('Error al obtener números de salida: ' . $e->getMessage());
            return 0.0; // En caso de error, devolver 0.0
        }
    }
}
