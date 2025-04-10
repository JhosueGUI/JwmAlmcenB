<?php

namespace App\Http\Controllers;

use App\Exports\SalidaCombustibleExport;
use App\Imports\SalidaCombustibleImport;
use App\Models\Articulo;
use App\Models\Combustible;
use App\Models\COMBUSTIBLE\Grifo;
use App\Models\DestinoCombustible;
use App\Models\Flota;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Transaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class SalidaCombustibleController extends Controller
{
    public function listarSalidaCombustible()
    {
        try {
            $salida_combustible = Combustible::with(['flota', 'personal.persona', 'destino_combustible', 'transaccion'])->where('estado_registro', 'A')->get();
            if (!$salida_combustible) {
                return response()->json(["resp" => "No se encontraron salidas de combustible"], 404);
            }
            return response()->json(['data' => $salida_combustible], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function crearSalidaCombustible(Request $request)
    {
        try {
            DB::beginTransaction();
            //traemos el producto
            $producto = Producto::where('estado_registro', 'A')->where('SKU', $request->SKU)->first();
            if (!$producto) {
                return response()->json(["error" => "Producto no encontrado"], 404);
            }
            $personal = Personal::where('estado_registro', 'A')->where('id', $request->personal_id)->first();
            //verificar existencia de destino
            $destino = DestinoCombustible::where('estado_registro', 'A')->where('id', $request->destino_combustible_id)->first();
            if (!$destino) {
                return response()->json(['resp' => 'Destino no Seleccionado'], 500);
            }
            //verificar si se ingreso el numero de salida
            if (!$request->numero_salida_combustible && !$request->numero_salida_ruta) {
                return response()->json(['resp' => 'Ingrese el Número de Salida'], 500);
            }

            $flota = Flota::where('estado_registro', 'A')->where('id', $request->flota_id)->first();
            //

            //traemos el articulo
            $articulo = Articulo::where('estado_registro', 'A')->where('id', $producto->articulo_id)->first();

            //numero de salida
            $numero_salida_combustible = $request->numero_salida_combustible;

            //calculamos el precio total
            $precio_total_soles = $numero_salida_combustible * $articulo->precio_soles;
            $precio_total_dolares = $numero_salida_combustible * $articulo->precio_dolares;

            $transaccion = Transaccion::create([
                'producto_id' => $producto->id,
                'tipo_operacion' => $request->tipo_operacion,
                'precio_unitario_soles' => $articulo->precio_soles,
                'precio_unitario_dolares' => $articulo->precio_dolares,
                'precio_total_soles' => $precio_total_soles,
                'precio_total_dolares' => $precio_total_dolares,
                'marca' => $request->marca,
                'observaciones' => $request->observaciones,
            ]);
            $fechaLocal = Carbon::parse($request->fecha)->format('Y-m-d');
            if ($request->numero_salida_combustible) {
                //traemos el inventario del producto
                $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();

                //traemos el total de salida
                $t_salida = $inventario->total_salida;
                //sumamos el total de salida
                $total_salida = $numero_salida_combustible + $t_salida;

                //calculamos el stock logico
                $total_ingreso = $inventario->total_ingreso;
                $stock_logico = $total_ingreso - $total_salida;

                $inventario->update([
                    'total_salida' => $total_salida,
                    'stock_logico' => $stock_logico
                ]);
                //traemos al inventario valorizado
                $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();
                //calculamos el valor de inventario
                $valor_inventario_soles = $stock_logico * $articulo->precio_soles;
                $valor_inventario_dolares = $stock_logico * $articulo->precio_dolares;

                $inventario_valorizado->update([
                    'valor_inventario_soles' => $valor_inventario_soles,
                    'valor_inventario_dolares' => $valor_inventario_dolares
                ]);
                //Calcular formulas
                $contometro_surtidor_inicial = Combustible::where('estado_registro', 'A')->orderBy('id', 'desc')->whereNotNull('contometro_surtidor')->first();
                $contometro_sutidor_nuevo = $request->contometro_surtidor;
                if (!$contometro_surtidor_inicial) {
                    $contometro_inicial = 26487;
                    $margen_error = $contometro_sutidor_nuevo - $contometro_inicial;
                    if ($contometro_sutidor_nuevo < $contometro_inicial) {
                        return response()->json(['resp' => 'Contometro Surtidor Incorrecto'], 500);
                    }
                } else {
                    $contometro_anterior = $contometro_surtidor_inicial->contometro_surtidor;
                    $margen_error = $contometro_sutidor_nuevo - $contometro_anterior;
                    if ($contometro_sutidor_nuevo < $contometro_anterior) {
                        return response()->json(['resp' => 'Contometro Surtidor Incorrecto'], 500);
                    }
                    $contometro_surtidor_inicial = $contometro_anterior;
                }
                if ($margen_error > $numero_salida_combustible + 2 || $margen_error < $numero_salida_combustible - 2) {
                    $resultado = "NO CUADRA";
                } else {
                    $resultado = "CONFORME";
                }

                Combustible::create([
                    'fecha' => $fechaLocal,
                    'destino_combustible_id' => $destino->id,
                    'personal_id' => $personal->id ?? null,
                    'flota_id' => $flota->id ?? null,
                    'transaccion_id' => $transaccion->id,
                    'numero_salida_stock' => $numero_salida_combustible,
                    //
                    'precio_unitario_soles' => $transaccion->precio_unitario_soles,
                    'precio_total_soles' => $transaccion->precio_total_soles,
                    //
                    'contometro_surtidor_inicial' => $contometro_surtidor_inicial,
                    'contometro_surtidor' => $contometro_sutidor_nuevo,
                    'margen_error_surtidor' => $margen_error,
                    'resultado' => $resultado,
                    'precinto_nuevo' => $request->precinto_nuevo,
                    'precinto_anterior' => $request->precinto_anterior,
                    'kilometraje' => $request->kilometraje,
                    'horometro' => $request->horometro,
                    'observacion' => $request->observacion,
                ]);
                if ($numero_salida_combustible > $stock_logico + $numero_salida_combustible) {
                    return response()->json(['resp' => 'Stock Insuficiente del Producto Seleccionado '], 500);
                }
            } else if ($request->numero_salida_ruta) {
                $grifo = Grifo::where('estado_registro', 'A')->where('id', $request->grifo_id)->first();
                if (!$grifo) {
                    return response()->json(['resp' => 'Grifo no Seleccionado'], 500);
                }
                $numero_salida_ruta = $request->numero_salida_ruta;
                $precio_unitario_soles = $request->precio_unitario_soles;
                if ($precio_unitario_soles <= 0) {
                    return response()->json(['resp' => 'Precio Unitario Incorrecto'], 500);
                }
                $precio_total_soles = $numero_salida_ruta * $precio_unitario_soles;
                $precio_igv = $precio_unitario_soles / 1.18;
                $precio_total_igv = round($precio_total_soles / 1.18, 1);


                Combustible::create([
                    'fecha' => $fechaLocal,
                    'destino_combustible_id' => $destino->id,
                    'personal_id' => $personal->id,
                    'flota_id' => $flota->id ?? null,
                    'grifo_id' => $grifo->id,
                    'tipo_comprobante' => $request->tipo_comprobante,
                    'numero_comprobante' => $request->numero_comprobante,
                    'numero_salida_ruta' => $numero_salida_ruta,
                    'precio_unitario_igv' => $precio_igv,
                    'precio_total_igv' => $precio_total_igv,
                    'precio_unitario_soles' => $precio_unitario_soles,
                    'precio_total_soles' => $precio_total_soles,
                    'precinto_nuevo' => $request->precinto_nuevo,
                    'precinto_anterior' => $request->precinto_anterior,
                    'kilometraje' => $request->kilometraje,
                    'horometro' => $request->horometro,
                    'observacion' => $request->observacion,
                ]);
            }

            DB::commit();
            return response()->json(['resp' => 'Salida registrada correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function EditarSalidaCombustible($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $combustible = Combustible::where('estado_registro', 'A')->find($id);
            if (!$combustible) {
                return response()->json(['resp' => 'Salida de Combustible no encontrada'], 404);
            }
            $fecha = $request->fecha;
            $combustible->update([
                "fecha" => $fecha,
            ]);
            DB::commit();
            return response()->json(['resp' => 'Salida de Combustible Actualizado Correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function ExportarConsumoPorPlaca(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $placa_solicitada = $request->placa;

            $query = Combustible::with(['flota', 'personal.persona', 'destino_combustible', 'transaccion', 'grifo'])
                ->where('estado_registro', 'A')
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);

            if ($placa_solicitada) {
                $placa = Flota::where('estado_registro', 'A')->where('placa', $placa_solicitada)->first();
                if ($placa) {
                    $query->where('flota_id', $placa->id);
                } else {
                    return response()->json(['mensaje' => 'La placa proporcionada no existe'], 404);
                }
            }

            $salida_combustible = $query->get();

            $resultadoAgrupado = $salida_combustible->groupBy(function ($item) {
                return $item->flota ? $item->flota->placa : 'Sin Unidad';
            });
            $dataForExport = $resultadoAgrupado->flatMap(function ($items, $placa) {
                return [
                    [
                        'placa' => $placa,
                        'detalle' => $items->map(function ($item) {
                            $fechaLocal = Carbon::parse($item->fecha)->format('d-m-Y');

                            return [
                                'id' => $item->id,
                                'fecha' => $fechaLocal,
                                'personal' => $item->personal->persona->nombre . ' ' . $item->personal->persona->apellido_paterno . ' ' . $item->personal->persona->apellido_materno,
                                'transaccion_id' => $item->transaccion_id,
                                'precio_unitario_soles' => $item->precio_unitario_soles,
                                'precio_total_soles' => $item->precio_total_soles,
                                'precio_total_igv' => $item->precio_total_igv,
                                'numero_salida_ruta' => $item->numero_salida_ruta,
                                'numero_salida_stock' => $item->numero_salida_stock,
                                'kilometraje' => $item->kilometraje,
                                'horometro' => $item->horometro,
                                'destino' => $item->destino_combustible->nombre,
                                'contometro' => $item->contometro_surtidor,
                                'margen_error' => $item->margen_error_surtidor,
                                'resultado' => $item->resultado,
                                'precinto_nuevo' => $item->precinto_nuevo,
                                'precinto_anterior' => $item->precinto_anterior,
                                'observacion' => $item->observacion,
                                'grifo' => $item->grifo ? $item->grifo->nombre : 'Sin Grifo',
                            ];
                        })
                    ]
                ];
            });

            // return response()->json(['data' => $dataForExport], 200);

            return Excel::download(new SalidaCombustibleExport($dataForExport), 'SalidaCombustible.xlsx');
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function GetStockCombustible()
    {
        try {

            $Sku_combustible = 537;
            $inventario = Inventario::with('producto.articulo')->where('producto_id', $Sku_combustible)->first();
            if (!$inventario) {
                return response()->json(['resp' => 'No se encontró el producto'], 404);
            }
            $data = [
                'nombre' => $inventario->producto->articulo->nombre,
                'stock' => $inventario->stock_logico,
                'alerta' => $inventario->stock_logico < 1200 ? 'Solicitar Combustible' : 'Combustible'
            ];
            return response()->json(['resp' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function subirSalidaCombustible(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'salida_combustible' => 'required|file|mimes:xlsx,xls'
            ]);

            $archivo = $request->file('salida_combustible');

            $nombreArchivo = $archivo->getClientOriginalName();

            if (Storage::exists('public/salida_combustible/' . $nombreArchivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }

            $archivo->storeAs('public/salida_combustible', $nombreArchivo);

            Excel::import(new SalidaCombustibleImport, storage_path('app/public/salida_combustible/' . $nombreArchivo));
            DB::commit();
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function elimarSalidaCombustible($id)
    {
        try {
            DB::beginTransaction();
            $salida_combustible = Combustible::with(['transaccion.producto.articulo'])->where('estado_registro', 'A')->find($id);
            if (!$salida_combustible) {
                return response()->json(['resp' => 'La salida de combustible ya se encuentra Inhabilitado'], 500);
            }
            $numero_salida_combustible = $salida_combustible->numero_salida_stock;
            if ($numero_salida_combustible > 0) {
                $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $salida_combustible->transaccion->producto_id)->first();
                //restar el numero de salida
                $total_salida = $inventario->total_salida - $numero_salida_combustible;
                $inventario->update([
                    'total_salida' => $total_salida,
                    'stock_logico' => $inventario->total_ingreso - $total_salida
                ]);
                $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->where('inventario_id', $inventario->id)->first();

                $inventario_valorizado->update([
                    'valor_inventario_soles' => $inventario->stock_logico * $salida_combustible->transaccion->producto->articulo->precio_soles,
                    'valor_inventario_dolares' => $inventario->stock_logico * $salida_combustible->transaccion->producto->articulo->precio_dolares
                ]);
                $transaccion = Transaccion::where('estado_registro', 'A')->where('id', $salida_combustible->transaccion_id)->first();
                $transaccion->delete();
            }

            $salida_combustible->delete();
            DB::commit();
            return response()->json(['resp' => 'Salida de Combustible Eliminado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
