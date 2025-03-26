<?php

namespace App\Http\Controllers;

use App\Exports\SalidaExport;
use App\Imports\SalidaImport;
use App\Models\Articulo;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Producto;
use App\Models\Salida;
use App\Models\Transaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ImportarSalidaJob;

class SalidaController extends Controller
{
    public function get()
    {
        try {
            // $salida = Salida::with([
            //     'personal.persona',
            //     'transaccion.producto.articulo.sub_familia.familia',
            //     'transaccion.producto.unidad_medida',
            //     'transaccion.producto.inventario.inventario_valorizado'
            // ])->where('estado_registro', 'A')->get();
            
            $salida = Salida::with([
                'personal:id,persona_id',
                'personal.persona:id,nombre,apellido_paterno,apellido_materno',
                'transaccion:id,marca,tipo_operacion,producto_id,precio_unitario_soles,precio_total_soles,precio_unitario_dolares,precio_total_dolares,observaciones',
                'transaccion.producto:id,SKU,articulo_id,unidad_medida_id',
                'transaccion.producto.unidad_medida:id,nombre',
                'transaccion.producto.inventario:id,producto_id,stock_logico',
                'transaccion.producto.articulo:id,nombre,precio_dolares,precio_soles,sub_familia_id',
                'transaccion.producto.articulo.sub_familia:id,nombre,familia_id',
                'transaccion.producto.articulo.sub_familia.familia:id,familia',
            ])->where('estado_registro', 'A')->get();
            
            if (!$salida) {
                return response()->json(['resp' => 'Salidas no Disponibles'], 500);
            }
            return response()->json(['data' => $salida], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($idSalida)
    {
        try {
            $salida = Salida::where('estado_registro', 'A')->find($idSalida);
            if (!$salida) {
                return response()->json(['resp' => 'Salida no Disponible'], 500);
            }
            return response()->json(['data' => $salida], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            //Traemos al producto segun el SKU
            $producto = Producto::where('estado_registro', 'A')->where('SKU', $request->SKU)->first();
            if (!$producto) {
                return response()->json(['resp' => 'Seleccione un Producto'], 500);
            }
            //Traemos al articulo que pertenece al producto
            $articulo = Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first();

            //ingresamos el  numero de salida
            $numero_salida = $request->numero_salida;

            if (!$numero_salida) {
                return response()->json(['resp' => 'Ingrese el número de Salida'], 500);
            }
            //calculamos el precio total segun el numero de salida
            $precio_total_soles = $numero_salida * $articulo->precio_soles;
            $precio_total_dolares = $numero_salida * $articulo->precio_dolares;

            //creamos Transaccion
            $transaccion = Transaccion::create([
                'producto_id' => $producto->id,
                'tipo_operacion' => $request->tipo_operacion,
                'precio_unitario_soles' => $articulo->precio_soles,
                'precio_unitario_dolares' => $articulo->precio_dolares,
                'precio_total_soles' => $precio_total_soles,
                'precio_total_dolares' => $precio_total_dolares,
                'marca' => $request->marca,
                'observaciones' => $request->observaciones
            ]);

            //traemos el inventario del producto
            $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();

            //traemos el total de salida
            $t_salida = $inventario->total_salida;
            //sumamos el total de salida con la salida nueva
            $total_salida = $numero_salida + $t_salida;

            //calculamos el stock logico 
            $total_ingreso = $inventario->total_ingreso;
            $stock_logico = $total_ingreso - $total_salida;

            $inventario->update([
                'total_salida' => $total_salida,
                'stock_logico' => $stock_logico
            ]);

            //traemos al inventario_valorizado
            $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();
            //calculamos el valor de inventario
            $valor_inventario_soles = $stock_logico * $articulo->precio_soles;
            $valor_inventario_dolares = $stock_logico * $articulo->precio_dolares;

            $inventario_valorizado->update([
                'valor_inventario_soles' => $valor_inventario_soles,
                'valor_inventario_dolares' => $valor_inventario_dolares
            ]);
            if (!$request->personal_id) {
                return response()->json(['resp' => 'Seleccione un Personal'], 500);
            }
            $salida = Salida::create([
                'fecha' => Carbon::now('America/Lima')->format('Y-m-d'),
                'vale' => $request->vale,
                'transaccion_id' => $transaccion->id,
                'destino' => $request->destino,
                'personal_id' => $request->personal_id,
                'unidad' => $request->unidad,
                'duracion_neumatico' => $request->duracion_neumatico,
                'kilometraje_horometro' => $request->kilometraje_horometro,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'numero_salida' => $numero_salida
            ]);
            if ($numero_salida > $stock_logico + $numero_salida) {
                return response()->json(['resp' => 'Stock Insuficiente del Producto Seleccionado '], 500);
            }
            DB::commit();
            return response()->json(['resp' => 'Salida registrada correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $idSalida)
    {
        try {
            DB::beginTransaction();

            //traemos al registro de la salida mediante el id
            $salida = Salida::where('estado_registro', 'A')->find($idSalida);
            if (!$salida) {
                return response()->json(['resp' => 'Salida no encontrado'], 404);
            }
            //Traemos a Transaccion correspondiente
            $transaccion = Transaccion::where('estado_registro', 'A')->where('id', $salida->transaccion_id)->first();
            if (!$transaccion) {
                return response()->json(['resp' => 'Transacción no encontrada'], 404);
            }

            //Traemos al producto segun el SKU
            $producto = Producto::where('estado_registro', 'A')->where('SKU', $request->SKU)->first();
            $producto_actual = Producto::where('estado_registro', 'A')->where('id', $transaccion->producto_id)->first();

            if (!$producto) {
                return response()->json(['resp' => 'Seleccione un Producto'], 500);
            }

            //Traemos al articulo que pertenece al producto
            $articulo = Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first();

            //traemos el inventario del producto
            $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();
            $inventario_actual = Inventario::where('estado_registro', 'A')->where('producto_id', $producto_actual->id)->first();


            //traemos al inventario_valorizado
            $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();

            //ingresamos el  numero de salida
            $numero_salida = $request->numero_salida;

            if (!$numero_salida) {
                return response()->json(['resp' => 'Ingrese el número de Salida'], 500);
            }
            if (!$request->personal_id) {
                return response()->json(['resp' => 'Seleccione un Personal'], 500);
            }


            $salida->update([
                'vale' => $request->vale,
                'destino' => $request->destino,
                'personal_id' => $request->personal_id,
                'unidad' => $request->unidad,
                'duracion_neumatico' => $request->duracion_neumatico,
                'kilometraje_horometro' => $request->kilometraje_horometro,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'numero_salida' => $numero_salida
            ]);


            //calculamos el precio total segun el numero de salida
            $precio_total_soles = $numero_salida * $articulo->precio_soles;
            $precio_total_dolares = $numero_salida * $articulo->precio_dolares;

            $transaccion->update([
                'producto_id' => $producto->id,
                'tipo_operacion' => $request->tipo_operacion,
                'precio_unitario_soles' => $articulo->precio_soles,
                'precio_unitario_dolares' => $articulo->precio_dolares,
                'precio_total_soles' => $precio_total_soles,
                'precio_total_dolares' => $precio_total_dolares,
                'marca' => $request->marca,
                'observaciones' => $request->observaciones
            ]);

            // Calcular Total Ingresos y Salidas
            $TotalIngresos = $this->getNumerosIngresoProducto($producto->id);
            $TotalSalidas = $this->getNumerosSalidaProducto($producto->id);
            $stock_logico = $TotalIngresos -  $TotalSalidas;

            ///validacion

            // $total_salidas_actual = $inventario_actual->total_salida;
            // $total_ingreso_actual = $inventario_actual->total_ingreso;

            $TotalIngresosActual = $this->getNumerosIngresoProducto($producto_actual->id);

            $TotalSalidaActual = $this->getNumerosSalidaProducto($producto_actual->id);
            $stock_logico_actual = $TotalIngresosActual - $TotalSalidaActual;



            if ($producto_actual->id === $producto->id) {
                $inventario->update([
                    'total_ingreso' => $TotalIngresos,
                    'total_salida' => $TotalSalidas,
                    'stock_logico' => $stock_logico,
                ]);
            } else {
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
                $salidaAnterior = $this->SalidaAnterior($producto_actual->id, $transaccion->id);

                $articulo_actual = Articulo::where('estado_registro', 'A')->where('id', $producto_actual->articulo_id)->first();
                $articulo_actual->update([
                    "precio_soles" => $salidaAnterior->precio_unitario_soles,
                    "precio_dolares" => $salidaAnterior->precio_unitario_dolares
                ]);

                $inventario_valorizado_actual = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario_actual->id)->first();
                $inventario_valorizado_actual->update([
                    "valor_unitario_soles" => $salidaAnterior->precio_unitario_soles,
                    "valor_inventario_soles" => ($stock_logico_actual + $numero_salida) * $salidaAnterior->precio_unitario_soles,
                    "valor_unitario_dolares" => $salidaAnterior->precio_unitario_dolares,
                    "valor_inventario_dolares" => ($stock_logico_actual + $numero_salida) * $salidaAnterior->precio_unitario_dolares,
                ]);
            }


            //calculamos el valor de inventario
            $valor_inventario_soles = $stock_logico * $articulo->precio_soles;
            $valor_inventario_dolares = $stock_logico * $articulo->precio_dolares;

            $inventario_valorizado->update([
                'valor_inventario_soles' => $valor_inventario_soles,
                'valor_inventario_dolares' => $valor_inventario_dolares
            ]);

            if ($numero_salida > $stock_logico + $numero_salida) {
                return response()->json(['resp' => 'Stock Insuficiente del Producto Seleccionado '], 500);
            }
            DB::commit();
            return response()->json(['resp' => 'Salida Actualizada correctamente'], 200);
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

    private function SalidaAnterior($idProducto, $idTransaccion)
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
    public function importarSalida(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validar el archivo subido
            $request->validate([
                'salida_excel' => 'required|file|mimes:xlsx,xls'
            ]);

            $archivo = $request->file('salida_excel');
            $nombre_archivo = $archivo->getClientOriginalName();

            // Verificar si el archivo ya existe en el almacenamiento
            if (Storage::exists('public/salida/' . $nombre_archivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }

            // Guardar el archivo en el almacenamiento público
            $archivo->storeAs('public/salida', $nombre_archivo);

            // Despachar el job para procesar la importación en segundo plano
            ImportarSalidaJob::dispatch(storage_path('app/public/salida/' . $nombre_archivo));

            DB::commit();

            // Retornar respuesta de éxito
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function exportarSalida()
    {
        return Excel::download(new SalidaExport(), 'Salida.xlsx');
    }
}
