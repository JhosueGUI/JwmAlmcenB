<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\Empresa;
use App\Models\FINANZA\Moneda;
use App\Models\FINANZA\Movimiento;
use App\Models\FINANZA\PersonaFinanza;
use App\Models\FINANZA\ProveedorFinanza;
use App\Models\Persona;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MovimientoController extends Controller
{
    public function get()
    {
        try {
            $movimientos = Movimiento::with(['modo', 'cliente', 'sub_categoria.categoria', 'empresa', 'estado', 'rendicion', 'sustento', 'moneda','persona','proveedor'])->where('estado_registro', 'A')->get();
            return response()->json(['data' => $movimientos], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            //traemos a la empresa
            $empresa = Empresa::where('id', $request->empresa_id)->first();
            if (!$empresa) {
                return response()->json(['error' => 'Empresa no encontrada'], 404);
            }
            //traemos a la moneda
            $moneda = Moneda::where('id', $request->moneda_id)->first();
            if (!$moneda) {
                return response()->json(['error' => 'Moneda no encontrada'], 404);
            }

            //Obtener ingreso
            $numero_ingreso = $request->ingreso;
            //Obtener egreso
            $numero_egreso = $request->egreso;
            //Calcular el total
            if ($request->ingreso) {
                if ($moneda->id === 1) {
                    $total_ingreso_soles = $empresa->total_ingreso_soles + $numero_ingreso;
                    $empresa->update([
                        'total_ingreso_soles' => $total_ingreso_soles
                    ]);
                } else if ($moneda->id === 2) {

                    $total_ingreso_dolares = $empresa->total_ingreso_dolares + $numero_ingreso;
                    $empresa->update([
                        'total_ingreso_dolares' => $total_ingreso_dolares
                    ]);
                }
                Movimiento::create([
                    'fecha' => $request->fecha,
                    'n_operacion' => $request->n_operacion,
                    'cliente_id' => $request->cliente_id,
                    'moneda_id' => $request->moneda_id,
                    'ingreso' => $numero_ingreso,
                    'descripcion' => $request->descripcion,
                    'solicitante' => $request->solicitante,
                    'sub_destino_placa' => $request->sub_destino_placa,
                    'sub_categoria_id' => $request->sub_categoria_id,
                    'estado_id' => $request->estado_id,
                    'rendicion_id' => $request->rendicion_id,
                    'serie' => $request->serie,
                    'n_factura' => $request->n_factura,
                    'fecha_factura' => $request->fecha_factura,
                    'obs' => $request->obs,
                    'n_retencion' => $request->n_retencion,
                    'fecha_retencion' => $request->fecha_retencion,
                    'empresa_id' => $empresa->id,
                    'sustento_id' => $request->sustento_id,
                ]);
            } else if ($request->egreso) {

                //Obtener persona finanza
                $persona_finanza = Persona::where('id', $request->persona_id)->first();
                if (!$persona_finanza) {
                    return response()->json(['error' => 'Persona Finanza no encontrada'], 404);
                }
                //Obtener proveedor finanza
                $proveedor_finanza = Proveedor::where('id', $request->proveedor_id)->first();
                if (!$proveedor_finanza) {
                    return response()->json(['error' => 'Proveedor Finanza no encontrada'], 404);
                }

                if ($moneda->id === 1) {
                    $total_egreso_soles = $empresa->total_egreso_soles + $numero_egreso;
                    $empresa->update([
                        'total_egreso_soles' => $total_egreso_soles
                    ]);
                } else if ($moneda->id === 2) {
                    $total_egreso_dolares = $empresa->total_egreso_dolares + $numero_egreso;
                    $empresa->update([
                        'total_egreso_dolares' => $total_egreso_dolares
                    ]);
                }
                Movimiento::create([
                    'fecha' => $request->fecha,
                    'modo_id' => $request->modo_id,
                    'n_operacion' => $request->n_operacion,
                    'proveedor_id' => $request->proveedor_id,
                    'persona_id' => $request->persona_id,
                    'moneda_id' => $request->moneda_id,
                    'egreso' => $numero_egreso,
                    'descripcion' => $request->descripcion,
                    'solicitante' => $request->solicitante,
                    'sub_destino_placa' => $request->sub_destino_placa,
                    'sub_categoria_id' => $request->sub_categoria_id,
                    'estado_id' => $request->estado_id,
                    'rendicion_id' => $request->rendicion_id,
                    'serie' => $request->serie,
                    'n_factura' => $request->n_factura,
                    'fecha_factura' => $request->fecha_factura,
                    'obs' => $request->obs,
                    'n_retencion' => $request->n_retencion,
                    'fecha_retencion' => $request->fecha_retencion,
                    'empresa_id' => $empresa->id,
                    'sustento_id' => $request->sustento_id,
                ]);
            }
            DB::commit();
            return response()->json(['resp' => 'Movimiento creado con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function crearTrazabilidad($idMovimiento, Request $request)
    {
        try {
            db::beginTransaction();
            $movimiento = Movimiento::where('id', $idMovimiento)->first();
            if (!$movimiento) {
                return response()->json(['resp' => 'Movimiento no encontrado'], 404);
            }
            $movimiento->update([
                'solicitante' => $request->solicitante,
                'sub_destino_placa' => $request->sub_destino_placa,
                'sub_categoria_id' => $request->sub_categoria_id,
                'estado_id' => $request->estado_id,
                'rendicion_id' => $request->rendicion_id,
                'serie' => $request->serie,
                'n_factura' => $request->n_factura,
                'fecha_factura' => $request->fecha_factura,
                'obs' => $request->obs,
                'n_retencion' => $request->n_retencion,
                'fecha_retencion' => $request->fecha_retencion,
            ]);

            db::commit();
            return response()->json(['resp' => "Trazabilidad creado correctamente"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function editarMovimiento(Request $request, $idMovimiento)
    {
        try {
            DB::beginTransaction();
            //traemos a la empresa
            $empresa = Empresa::where('id', $request->empresa_id)->first();
            if (!$empresa) {
                return response()->json(['error' => 'Empresa no encontrada'], 404);
            }
            //traemos a la moneda
            $moneda = Moneda::where('id', $request->moneda_id)->first();
            if (!$moneda) {
                return response()->json(['error' => 'Moneda no encontrada'], 404);
            }
            //traemos al movimiento
            $movimiento = Movimiento::where('id', $idMovimiento)->first();
            if (!$movimiento) {
                return response()->json(['error' => 'Movimiento no encontrado'], 404);
            }

            //Obtener ingreso
            $numero_ingreso = $request->ingreso;
            //Obtener egreso
            $numero_egreso = $request->egreso;

            if ($movimiento->ingreso > 0) {
                if ($movimiento->empresa_id === $empresa->id) {
                    if ($movimiento->moneda_id === 1) {
                        $empresa_ingreso_soles = ($empresa->total_ingreso_soles - $movimiento->ingreso) + $numero_ingreso;
                        $empresa->update([
                            'total_ingreso_soles' => $empresa_ingreso_soles
                        ]);
                    } else if ($movimiento->moneda_id === 2) {
                        $empresa_ingreso_dolares = ($empresa->total_ingreso_dolares - $movimiento->ingreso) + $numero_ingreso;
                        $empresa->update([
                            'total_ingreso_dolares' => $empresa_ingreso_dolares
                        ]);
                    }
                } else if ($movimiento->empresa_id !== $empresa->id) {
                    $empresa_anterior = $movimiento->empresa;
                    $empresa_nueva = $empresa;

                    if ($movimiento->moneda_id === 1) {
                        $empresa_ingreso_soles = ($movimiento->empresa->total_ingreso_soles - $movimiento->ingreso);
                        $empresa_anterior->update([
                            'total_ingreso_soles' => $empresa_ingreso_soles
                        ]);
                    } else if ($movimiento->moneda_id === 2) {
                        $empresa_ingreso_dolares = ($movimiento->empresa->total_ingreso_dolares - $movimiento->ingreso);
                        $empresa_anterior->update([
                            'total_ingreso_dolares' => $empresa_ingreso_dolares
                        ]);
                    }
                    if ($moneda->id === 1) {
                        $empresa_ingreso_soles = ($empresa->total_ingreso_soles + $numero_ingreso);
                        $empresa_nueva->update([
                            'total_ingreso_soles' => $empresa_ingreso_soles
                        ]);
                    } else if ($moneda->id === 2) {
                        $empresa_ingreso_dolares = ($empresa->total_ingreso_dolares + $numero_ingreso);
                        $empresa_nueva->update([
                            'total_ingreso_dolares' => $empresa_ingreso_dolares
                        ]);
                    }
                }
                $movimiento->update([
                    'fecha' => $request->fecha,
                    'n_operacion' => $request->n_operacion,
                    'cliente_id' => $request->cliente_id,
                    'moneda_id' => $moneda->id,
                    'ingreso' => $numero_ingreso,
                    'empresa_id' => $empresa->id,
                    'descripcion' => $request->descripcion,
                ]);
            } else if ($movimiento->egreso > 0) {
                if ($movimiento->empresa_id === $empresa->id) {
                    if ($movimiento->moneda_id === 1) {
                        $empresa_egreso_soles = ($empresa->total_egreso_soles - $movimiento->egreso) + $numero_egreso;
                        $empresa->update([
                            'total_egreso_soles' => $empresa_egreso_soles
                        ]);
                    } else if ($movimiento->moneda_id === 2) {
                        $empresa_egreso_dolares = ($empresa->total_egreso_dolares - $movimiento->egreso) + $numero_egreso;
                        $empresa->update([
                            'total_egreso_dolares' => $empresa_egreso_dolares
                        ]);
                    }
                } else if ($movimiento->empresa_id !== $empresa->id) {

                    $empresa_anterior = $movimiento->empresa;
                    $empresa_nueva = $empresa;

                    if ($movimiento->moneda_id === 1) {
                        $empresa_egreso_soles = ($movimiento->empresa->total_egreso_soles - $movimiento->egreso);
                        $empresa_anterior->update([
                            'total_egreso_soles' => $empresa_egreso_soles
                        ]);
                    } else if ($movimiento->moneda_id === 2) {
                        $empresa_egreso_dolares = ($movimiento->empresa->total_egreso_dolares - $movimiento->egreso);
                        $empresa_anterior->update([
                            'total_egreso_dolares' => $empresa_egreso_dolares
                        ]);
                    }
                    if ($moneda->id === 1) {
                        $empresa_egreso_soles = ($empresa->total_egreso_soles + $numero_egreso);
                        $empresa_nueva->update([
                            'total_egreso_soles' => $empresa_egreso_soles
                        ]);
                    } else if ($moneda->id === 2) {
                        $empresa_egreso_dolares = ($empresa->total_egreso_dolares + $numero_egreso);
                        $empresa_nueva->update([
                            'total_egreso_dolares' => $empresa_egreso_dolares
                        ]);
                    }
                }
                $movimiento->update([
                    'fecha' => $request->fecha,
                    'modo_id' => $request->modo_id,
                    'n_operacion' => $request->n_operacion,
                    'persona_id' => $request->persona_id,
                    'proveedor_id' => $request->proveedor_id,
                    'moneda_id' => $moneda->id,
                    'egreso' => $numero_egreso,
                    'empresa_id' => $empresa->id,
                    'descripcion' => $request->descripcion,
                ]);
            }
            DB::commit();
            return response()->json(['resp' => 'Movimiento editado con éxito'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function delete($idMovimiento){
        try{
            DB::beginTransaction();
            $movimiento = Movimiento::where('id', $idMovimiento)->first();
            if (!$movimiento) {
                return response()->json(['error' => 'Movimiento no encontrado'], 404);
            }
            $empresa = Empresa::where('id', $movimiento->empresa_id)->first();
            if (!$empresa) {
                return response()->json(['error' => 'Empresa no encontrada'], 404);
            }
            if($movimiento->ingreso > 0){
                if($movimiento->moneda_id === 1){
                    $empresa->update([
                        'total_ingreso_soles' => $empresa->total_ingreso_soles - $movimiento->ingreso
                    ]);
                }else if($movimiento->moneda_id === 2){
                    $empresa->update([
                        'total_ingreso_dolares' => $empresa->total_ingreso_dolares - $movimiento->ingreso
                    ]);
                }
            }else if($movimiento->egreso > 0){
                if($movimiento->moneda_id === 1){
                    $empresa->update([
                        'total_egreso_soles' => $empresa->total_egreso_soles - $movimiento->egreso
                    ]);
                }else if($movimiento->moneda_id === 2){
                    $empresa->update([
                        'total_egreso_dolares' => $empresa->total_egreso_dolares - $movimiento->egreso
                    ]);
                }
            }
            $movimiento->delete();
            DB::commit();
            return response()->json(['resp' => 'Movimiento eliminado con éxito'], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
