<?php

namespace App\Imports;

use App\Models\Articulo;
use App\Models\Ingreso;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ProveedorProducto;
use App\Models\SubFamilia;
use App\Models\Transaccion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class IngresoImport implements ToCollection, WithCalculatedFormulas
{
    /**
     * Maneja la importación de datos desde una colección de Excel.
     *
     * @param Collection $collection La colección de datos de la hoja de Excel.
     */
    public function collection(Collection $columnas)
    {
        DB::transaction(function () use ($columnas) {
            foreach ($columnas as $index => $columna) {
                if ($index === 0 || count($columna) < 4) {
                    Log::warning('Fila con datos incompletos o de encabezado: ' . json_encode($columna));
                    continue;
                }

                // Extrae y convierte los valores de cada columna.
                $fecha = date('Y-m-d', strtotime($columna[0]));

                $guia_remision = $columna[1] ?? null;
                $tipo_operacion = $columna[2] ?? null;
                $tipo_cp = $columna[3] ?? null;
                $documento = $columna[4] ?? null;
                $orden_compra = $columna[5] ?? null;
                $codigo_proveedor = $columna[6] ?? null;
                $codigo_producto = $columna[8] ?? null;
                $marca = $columna[12] ?? null;
                $numero_ingreso = intval($columna[14] ?? 0); // Convertir a entero
                $precio_unitario_soles = floatval($columna[15] ?? 0); // Convertir a flotante
                $precio_unitario_dolares = floatval($columna[16] ?? 0); // Convertir a flotante
                $observaciones = $columna[18] ?? null;

                $producto = $codigo_producto ? Producto::where('estado_registro', 'A')->where('SKU', $codigo_producto)->first() : null;
                $articulo = $producto ? Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first() : null;

                if ($articulo) {
                    // Verificar si el precio en dólares es mayor que 0
                    if ($precio_unitario_dolares > 0) {
                        // Convertir el precio a soles
                        $conversion = $this->Convertir($precio_unitario_dolares);
                        $precio_unitario_soles = $conversion['monto_pen'];
                        $articulo->update([
                            'precio_soles' => $precio_unitario_soles,
                            'precio_dolares' => $precio_unitario_dolares
                        ]);
                    } else {
                        // Si el precio en dólares es igual a 0, usar el precio en soles
                        $articulo->update([
                            'precio_soles' => $precio_unitario_soles,
                            'precio_dolares' => 0
                        ]);
                    }
                }


                if ($producto) {
                    // Crear la transacción
                    $transaccion = Transaccion::create([
                        'producto_id' => $producto->id,
                        'tipo_operacion' => $tipo_operacion,
                        'precio_unitario_soles' => $precio_unitario_soles,
                        'precio_total_soles' => $precio_unitario_soles * $numero_ingreso,
                        'precio_unitario_dolares' => $precio_unitario_dolares,
                        'precio_total_dolares' => $precio_unitario_dolares * $numero_ingreso,
                        'marca' => $marca,
                        'observaciones' => $observaciones
                    ]);

                    // Crear el ingreso
                    $ingreso = Ingreso::create([
                        'fecha' => $fecha,
                        'guia_remision' => $guia_remision,
                        'tipo_cp' => $tipo_cp,
                        'documento' => $documento,
                        'orden_compra' => $orden_compra,
                        'numero_ingreso' => $numero_ingreso,
                        'transaccion_id' => $transaccion->id,
                    ]);

                    // Obtener o crear el inventario
                    $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();
                    if (!$inventario) {
                        $inventario = Inventario::create(['producto_id' => $producto->id]);
                    }
                    $total_ingreso = $this->getNumerosIngresoProducto($producto->id);
                    $total_salida = $this->getNumerosSalidaProducto($producto->id);
                    $stock_logico = $total_ingreso - $total_salida;

                    $inventario->update([
                        'total_ingreso' => $total_ingreso,
                        'stock_logico' => $stock_logico,
                    ]);

                    // Obtener o crear inventario valorizado
                    $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();
                    if (!$inventario_valorizado) {
                        $inventario_valorizado = InventarioValorizado::create(['inventario_id' => $inventario->id]);
                    }
                    $inventario_valorizado->update([
                        'valor_unitario_soles' => $precio_unitario_soles,
                        'valor_unitario_dolares' => $precio_unitario_dolares,
                        'valor_inventario_soles' => $stock_logico * $precio_unitario_soles,
                        'valor_inventario_dolares' => $stock_logico * $precio_unitario_dolares,
                    ]);

                    // Obtener el proveedor
                    $proveedor = Proveedor::where('id', $codigo_proveedor)->first();
                    if ($proveedor) {
                        ProveedorProducto::create([
                            'proveedor_id' => $proveedor->id,
                            'producto_id' => $producto->id,
                            'identificador' => $transaccion->id
                        ]);
                    }
                }
            }
        });
    }


    /**
     * Obtiene el total de números de ingreso para un producto.
     *
     * @param int $idProducto
     * @return int
     */
    private function getNumerosIngresoProducto($idProducto)
    {
        try {
            $producto = Producto::with('transaccion.ingreso')->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return 0; // Producto no encontrado, devolver 0 en lugar de respuesta JSON
            }

            $totalNumerosIngreso = 0;
            foreach ($producto->transaccion as $transaccion) {
                foreach ($transaccion->ingreso as $ingreso) {
                    $totalNumerosIngreso += (int) $ingreso->numero_ingreso;
                }
            }

            return $totalNumerosIngreso;
        } catch (\Exception $e) {
            Log::error('Error al obtener números de ingreso: ' . $e->getMessage());
            return 0; // En caso de error, devolver 0
        }
    }

    /**
     * Obtiene el total de números de salida para un producto.
     *
     * @param int $idProducto
     * @return int
     */
    private function getNumerosSalidaProducto($idProducto)
    {
        try {
            $producto = Producto::with('transaccion.salida')->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return 0; // Producto no encontrado, devolver 0 en lugar de respuesta JSON
            }

            $totalNumerosSalida = 0;
            foreach ($producto->transaccion as $transaccion) {
                foreach ($transaccion->salida as $salida) {
                    $totalNumerosSalida += (int) $salida->numero_salida;
                }
            }

            return $totalNumerosSalida;
        } catch (\Exception $e) {
            Log::error('Error al obtener números de salida: ' . $e->getMessage());
            return 0; // En caso de error, devolver 0
        }
    }

    private function Convertir($monto_dolar)
    {
        $apiKey = '50318e4169244277638a33a1';
        $cacheKey = "tasa_cambio_usd_pen";

        // Intentar obtener la tasa de cambio desde la caché (válida por 1 hora)
        $tasaCambio = Cache::remember($cacheKey, 3600, function () use ($apiKey) {
            try {
                $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD");
                if ($response->successful()) {
                    return round($response->json()['conversion_rates']['PEN'], 2);
                }
            } catch (\Exception $e) {
                return null;
            }
        });

        // Si no se pudo obtener la tasa, retornar un error
        if (!$tasaCambio) {
            return [
                'error' => 'No se pudo obtener la tasa de cambio'
            ];
        }

        // Realizar la conversión
        $montoPen = round($monto_dolar * $tasaCambio, 2);

        return [
            'monto_usd' => $monto_dolar,
            'monto_pen' => $montoPen,
            'tasa_cambio' => $tasaCambio
        ];
    }
}
