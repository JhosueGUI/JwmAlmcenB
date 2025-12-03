<?php

namespace App\Http\Controllers;

use App\Exports\IngresoExport;
use App\Imports\IngresoImport;
use App\Models\Articulo;
use App\Models\Ingreso;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ProveedorProducto;
use App\Models\Transaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class IngresoController extends Controller
{
    public function get()
    {
        try {
            $ingresos = Ingreso::with([
                'transaccion:id,precio_unitario_soles,precio_total_soles,precio_unitario_dolares,precio_total_dolares,observaciones,tipo_operacion,marca,producto_id',
                'transaccion.producto:id,SKU,articulo_id,unidad_medida_id',
                'transaccion.producto.unidad_medida:id,nombre',
                'transaccion.producto.proveedor_producto:id,proveedor_id,producto_id,identificador',
                'transaccion.producto.proveedor_producto.proveedor:id,razon_social',
                'transaccion.producto.articulo:id,nombre,precio_dolares,precio_soles,sub_familia_id',
                'transaccion.producto.articulo.sub_familia:id,nombre,familia_id',
                'transaccion.producto.articulo.sub_familia.familia:id,familia',
                'transaccion.producto.inventario:id,producto_id,stock_logico',
            ])->get();
            // $ingresos = Ingreso::with([
            //     'transaccion.producto.articulo.sub_familia.familia',
            //     'transaccion.producto.unidad_medida',
            //     'transaccion.producto.inventario',
            //     'transaccion.producto.proveedor_producto.proveedor',
            // ])->where('estado_registro', 'A')->get();
            // // Recorrer cada ingreso y ajustar la estructura de proveedor_producto
            foreach ($ingresos as $ingreso) {
                $transaccion = $ingreso->transaccion;
                // Filtrar proveedor_producto por identificador
                $proveedor_producto = $transaccion->producto->proveedor_producto
                    ->where('identificador', $transaccion->id)->first();
                // Agregar proveedor_producto al objeto transaccion
                $transaccion->proveedor_producto = $proveedor_producto;
                // Ocultar el array de proveedor producto dentro de producto
                $transaccion->producto->makeHidden(['proveedor_producto']);
            }
            return response()->json(['data' => $ingresos], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            // Verificar existencia del proveedor
            $proveedor = Proveedor::where('estado_registro', 'A')->find($request->proveedor_id);
            if (!$proveedor) {
                return response()->json(['resp' => 'Proveedor no seleccionado '], 500);
            }

            if (!$request->productos || !is_array($request->productos)) {
                return response()->json(['resp' => 'Productos no proporcionados o formato incorrecto'], 400);
            }
            $fechaLocal = Carbon::parse($request->fecha)->format('Y-m-d');
            foreach ($request->productos as $productoData) {
                // Verificar existencia del producto
                $producto = Producto::where('estado_registro', 'A')->where('SKU', $productoData['SKU'])->first();
                if (!$producto) {
                    return response()->json(['resp' => 'Producto con SKU ' . $productoData['SKU'] . ' no encontrado'], 404);
                }

                if (!isset($productoData['numero_ingreso']) || !is_numeric($productoData['numero_ingreso'])) {
                    return response()->json(['resp' => 'Número de ingreso no válido para SKU ' . $productoData['SKU']], 400);
                }

                $precio_unitario_soles = isset($productoData['precio_unitario_soles']) ? $productoData['precio_unitario_soles'] : null;
                $precio_unitario_dolares = isset($productoData['precio_unitario_dolares']) ? $productoData['precio_unitario_dolares'] : null;

                // Actualizar precios del artículo asociado al producto
                $articulo = Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first();
                if ($precio_unitario_soles) {
                    $articulo->update(['precio_soles' => $precio_unitario_soles]);
                } elseif ($precio_unitario_dolares) {
                    $conversion = $this->probar($precio_unitario_dolares);
                    $precio_unitario_soles = $conversion['monto_pen'];
                    $articulo->update([
                        'precio_soles' => $precio_unitario_soles,
                        'precio_dolares' => $precio_unitario_dolares
                    ]);
                }

                // Crear transacción
                $numero_ingreso = $productoData['numero_ingreso'];
                $precio_total_soles = $numero_ingreso * $precio_unitario_soles;
                $precio_total_dolares = $numero_ingreso * $precio_unitario_dolares;

                $transaccion = Transaccion::create([
                    'producto_id' => $producto->id,
                    'tipo_operacion' => $request->tipo_operacion,
                    'precio_unitario_soles' => $precio_unitario_soles,
                    'precio_total_soles' => $precio_total_soles,
                    'precio_unitario_dolares' => $precio_unitario_dolares,
                    'precio_total_dolares' => $precio_total_dolares,
                    'marca' => $productoData['marca'],
                    'observaciones' => $request->observaciones
                ]);

                // Crear ingreso
                $ingreso = Ingreso::create([
                    //
                    'fecha' => $fechaLocal,
                    'guia_remision' => $request->guia_remision,
                    'tipo_cp' => $request->tipo_cp,
                    'documento' => $request->documento,
                    'orden_compra' => $request->orden_compra,
                    'numero_ingreso' => $numero_ingreso,
                    'transaccion_id' => $transaccion->id,
                    'personal_id' => $request->personal_id
                ]);

                // Obtener inventario
                $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();

                // Actualizar inventario
                $total_ingreso = $inventario->total_ingreso + $numero_ingreso;
                $numero_salida = $inventario->total_salida;
                $stock_logico = $total_ingreso - $numero_salida;

                $inventario->update([
                    'total_ingreso' => $total_ingreso,
                    'total_salida' => $numero_salida,
                    'stock_logico' => $stock_logico,
                ]);

                // Obtener inventario_valorizado
                $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();

                // Actualizar inventario valorizado
                $inventario_valorizado->update([
                    'valor_unitario_soles' => $precio_unitario_soles,
                    'valor_unitario_dolares' => $precio_unitario_dolares,
                    'valor_inventario_soles' => $stock_logico * $precio_unitario_soles,
                    'valor_inventario_dolares' => $stock_logico * $precio_unitario_dolares,
                ]);

                // Crear relación entre proveedor y producto
                ProveedorProducto::create([
                    'proveedor_id' => $proveedor->id,
                    'producto_id' => $producto->id,
                    'identificador' => $transaccion->id
                ]);
            }

            DB::commit();

            return response()->json(["resp" => "Ingresos creados exitosamente"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $idIngreso)
    {
        try {
            DB::beginTransaction();

            // Obtener el ingreso actual
            $ingreso = Ingreso::where('estado_registro', 'A')->find($idIngreso);
            if (!$ingreso) {
                return response()->json(['resp' => 'Ingreso no encontrado'], 404);
            }

            // Obtener la transacción actual
            $transaccion = Transaccion::where('estado_registro', 'A')->where('id', $ingreso->transaccion_id)->first();
            if (!$transaccion) {
                return response()->json(['resp' => 'Transacción no encontrada'], 404);
            }


            // Verificar existencia del proveedor
            $proveedor = Proveedor::where('estado_registro', 'A')->find($request->proveedor_id);
            if (!$proveedor) {
                return response()->json(['resp' => 'Proveedor no seleccionado'], 500);
            }
            $fechaLocal = Carbon::parse($request->fecha)->format('Y-m-d');
            //Inventario del Producto actual
            $inventario_producto_actual = Producto::with('inventario')->where('estado_registro', 'A')->where('id', $transaccion->producto_id)->first();
            $inventario_actual = Inventario::where('estado_registro', 'A')->where('producto_id', $inventario_producto_actual->id)->first();

            // $numero_ingreso_actual = $inventario_actual->total_ingreso;
            // $numero_salida_actual = $inventario_actual->total_salida;
            // $stock_logico_actual = $numero_ingreso_actual + $numero_salida_actual;

            // Verificar existencia del producto
            $producto = Producto::where('estado_registro', 'A')->where('SKU', $request->SKU)->first();
            $producto_actual = Producto::where('estado_registro', 'A')->where('id', $transaccion->producto_id)->first();

            if (!$producto) {
                return response()->json(['resp' => 'Producto no seleccionado'], 404);
            }


            //#region de Validaciones
            if (!$request->numero_ingreso) {
                return response()->json(['resp' => 'Ingrese el Número de Ingreso'], 500);
            }

            // Actualizar Proveedor Producto
            $producto_proveedor = ProveedorProducto::where('estado_registro', 'A')->where('identificador', $transaccion->id)->first();
            if (!$producto_proveedor) {
                ProveedorProducto::create([
                    'proveedor_id' => $proveedor->id,
                    'producto_id' => $producto->id,
                    'identificador' => $transaccion->id
                ]);
            } else {
                $producto_proveedor->update([
                    'proveedor_id' => $proveedor->id,
                    'producto_id' => $producto->id
                ]);
            }
            // Actualizar precios del artículo asociado al producto
            $precio_unitario_soles = $request->precio_unitario_soles;
            $precio_unitario_dolares = $request->precio_unitario_dolares;

            $articulo = Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first();
            if ($precio_unitario_dolares) {
                $conversion = $this->probar($precio_unitario_dolares);
                $precio_unitario_soles = $conversion['monto_pen'];
                $articulo->update([
                    'precio_soles' => $precio_unitario_soles,
                    'precio_dolares' => $precio_unitario_dolares
                ]);
            } else if ($precio_unitario_soles) {
                $articulo->update([
                    'precio_soles' => $precio_unitario_soles,
                    'precio_dolares' => 0
                ]);
            }
            
            // Crear transacción
            $numero_ingreso = $request->numero_ingreso;
            $precio_total_soles = $numero_ingreso * $precio_unitario_soles;
            $precio_total_dolares = $numero_ingreso * $precio_unitario_dolares;

            $transaccion->update([
                'producto_id' => $producto->id,
                'tipo_operacion' => $request->tipo_operacion,
                'precio_unitario_soles' => $precio_unitario_soles,
                'precio_total_soles' => $precio_total_soles,
                'precio_unitario_dolares' => $precio_unitario_dolares,
                'precio_total_dolares' => $precio_total_dolares,
                'marca' => $request->marca,
                'observaciones' => $request->observaciones
            ]);

            // Actualizar ingreso
            $ingreso->update([
                'fecha' => $fechaLocal,
                'guia_remision' => $request->guia_remision,
                'tipo_cp' => $request->tipo_cp,
                'documento' => $request->documento,
                'orden_compra' => $request->orden_compra,
                'numero_ingreso' => $numero_ingreso,
                'transaccion_id' => $transaccion->id,
            ]);

            // Obtener inventario
            $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();

            if (!$inventario) {
                return response()->json(['resp' => 'Inventario no encontrado'], 404);
            }

            // Calcular Total Ingresos y Salidas
            $TotalIngresos = $this->getNumerosIngresoProducto($producto->id);
            $TotalSalidas = $this->getNumerosSalidaProducto($producto->id);

            //Calcular el total Ingresos y Salidas del Producto NO Seleccionado
            $TotalIngresosActual = $this->getNumerosIngresoProducto($producto_actual->id);
            $TotalSalidaActual = $this->getNumerosSalidaProducto($producto_actual->id);
            $stock_logico_actual = $TotalIngresosActual - $TotalSalidaActual;

            // Calcular Stock Lógico
            $stock_logico = $TotalIngresos - $TotalSalidas;

            // Actualizar inventario según la condición
            if ($inventario_producto_actual->id === $producto->id) {
                // Si los productos son iguales, actualiza los datos con la suma de ingresos y salidas
                $inventario->update([
                    'total_ingreso' => $TotalIngresos,
                    'total_salida' => $TotalSalidas,
                    'stock_logico' => $stock_logico,
                ]);
            } else {
                //si se selecciona otro producto
                $inventario_actual->update([
                    'total_ingreso' => $TotalIngresosActual,
                    'total_salida' => $TotalSalidaActual,
                    'stock_logico' => $stock_logico_actual,
                ]);
                $inventario->update([
                    'total_ingreso' => $TotalIngresos,
                    'total_salida' => $TotalSalidas,
                    'stock_logico' => $stock_logico,
                ]);
                $ingresoAnterior = $this->IngresoAnterior($producto_actual->id, $transaccion->id);
                $articulo_actual = Articulo::where('estado_registro', 'A')->where('id', $producto_actual->articulo_id)->first();
                $articulo_actual->update([
                    "precio_soles" => $ingresoAnterior->precio_unitario_soles ?? 0,
                    "precio_dolares" => $ingresoAnterior->precio_unitario_dolares ?? 0
                ]);
                $inventario_valorizado_actual = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario_actual->id)->first();

                $valor_unitario_soles = $ingresoAnterior ? $ingresoAnterior->precio_unitario_soles : 0;
                $valor_unitario_dolares = $ingresoAnterior ? $ingresoAnterior->precio_unitario_dolares : 0;

                // Calcula el valor de inventario con los valores predeterminados en caso de que $ingresoAnterior sea null
                $valor_inventario_soles = ($stock_logico_actual - $numero_ingreso) * $valor_unitario_soles;
                $valor_inventario_dolares = ($stock_logico_actual - $numero_ingreso) * $valor_unitario_dolares;

                // Actualiza el inventario valorizado con los valores calculados
                $inventario_valorizado_actual->update([
                    "valor_unitario_soles" => $valor_unitario_soles,
                    "valor_inventario_soles" => $valor_inventario_soles,
                    "valor_unitario_dolares" => $valor_unitario_dolares,
                    "valor_inventario_dolares" => $valor_inventario_dolares,
                ]);
                // return response()->json($ingresoAnterior);

            }

            // Obtener inventario_valorizado
            $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();
            if (!$inventario_valorizado) {
                return response()->json(['resp' => 'Inventario Valorizado no encontrado'], 404);
            }

            // Actualizar inventario valorizado
            $inventario_valorizado->update([
                'valor_unitario_soles' => $precio_unitario_soles,
                'valor_unitario_dolares' => $precio_unitario_dolares,
                'valor_inventario_soles' => $stock_logico * $precio_unitario_soles,
                'valor_inventario_dolares' => $stock_logico * $precio_unitario_dolares,
            ]);

            DB::commit();

            return response()->json(["resp" => "Ingreso Actualizado exitosamente"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }

    private function getNumerosIngresoProducto($idProducto)
    {
        try {
            $producto = Producto::with('transaccion.ingreso')->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return response()->json(["error" => "Producto no encontrado"], 404);
            }

            $totalNumerosIngreso = 0;
            $totalArrayIngreso = [];
            foreach ($producto->transaccion as $transaccion) {
                foreach ($transaccion->ingreso as $ingreso) {
                    $totalArrayIngreso[] = $ingreso->numero_ingreso;
                    $totalNumerosIngreso += (float) $ingreso->numero_ingreso;
                }
            }

            return $totalNumerosIngreso;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    private function getNumerosSalidaProducto($idProducto)
    {
        try {
            $producto = Producto::with('transaccion.salida')->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return response()->json(["error" => "Producto no encontrado"], 404);
            }

            $totalNumerosSalida = 0;
            $totalArraySalida = [];
            foreach ($producto->transaccion as $transaccion) {
                foreach ($transaccion->salida as $salida) {
                    $totalArraySalida[] = $salida->numero_salida;
                    $totalNumerosSalida += (float) $salida->numero_salida;
                }
            }
            return $totalNumerosSalida;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }

    private function IngresoAnterior($idProducto, $idTransaccion)
    {
        try {
            $producto = Producto::with(['transaccion' => function ($query) use ($idTransaccion) {
                $query->where('id', '<>', $idTransaccion)->orderByDesc('id')->take(1);
            }])->where('estado_registro', 'A')->find($idProducto);

            if (!$producto) {
                return response()->json(["error" => "Producto no encontrado"], 404);
            }

            // Obtener la última transacción diferente a la actual
            $ultimaTransaccion = $producto->transaccion->first();

            return $ultimaTransaccion;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }


    public function delete($idIngreso)
    {
        try {
            DB::beginTransaction();
            $ingreso = Ingreso::where('estado_registro', 'A')->find($idIngreso);
            $transaccion = Transaccion::where('estado_registro', 'A')->where('id', $ingreso->transaccion_id)->first();
            $ingreso->update([
                'estado_registro' => 'I'
            ]);
            $transaccion->update([
                'estado_registro' => 'I'
            ]);


            return response()->json($ingreso);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function importarIngreso(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'ingreso_excel' => 'required|file|mimes:xlsx,xls'
            ]);
            $archivo = $request->file('ingreso_excel');
            $nombre_archivo = $archivo->getClientOriginalName();
            if (Storage::exists('public/ingreso/' . $nombre_archivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }
            $archivo->storeAs('public/ingreso', $nombre_archivo);
            Excel::import(new IngresoImport, storage_path('app/public/ingreso/' . $nombre_archivo));
            DB::commit();
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    private function probar($monto_dolar)
    {
        // Obtener la tasa de cambio desde la API
        $apiKey = '50318e4169244277638a33a1';
        $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD");
        $tasaCambio = $response->json()['conversion_rates']['PEN'];

        // Truncar la tasa de cambio a dos decimales
        $tasaCambio = floor($tasaCambio * 100) / 100;

        // Realizar la conversión
        $montoUsd = $monto_dolar;
        $montoPen = $montoUsd * $tasaCambio;

        return [
            'monto_usd' => $montoUsd,
            'monto_pen' => $montoPen,
            'tasa_cambio' => $tasaCambio
        ];
    }
    public function exportarIngreso()
    {
        return Excel::download(new IngresoExport, 'Ingreso.xlsx');
    }
}
