<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\AccesoRol;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    public function get()
    {
        try {
            $rol = Rol::where('estado_registro', 'A')->get();
            if (!$rol) {
                return response()->json(['resp' => 'Roles no existentes'], 200);
            }
            return response()->json(['data' => $rol], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($rolID)
    {
        try {
            $rol = Rol::where('estado_registro', 'A')->find($rolID);
            if (!$rol) {
                return response()->json(['resp' => 'Rol no existe'], 200);
            }
            return response()->json(['resp' => $rol], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $rol = Rol::create([
                'nombre' => $request->nombre,
                'tipo_acceso' => 3
            ]);
            DB::commit();
            return response()->json(['resp' => 'Rol creado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $rolID)
    {
        try {
            DB::beginTransaction();
            $rol = Rol::where('estado_registro', 'A')->find($rolID);
            if (!$rol) {
                return response()->json(['resp' => 'El Rol no esta Disponible'], 200);
            }
            $rol->update([
                'nombre' => $request->nombre,
                'tipo_acceso' => 3
            ]);
            DB::commit();
            return response()->json(['resp' => 'Rol Actualizado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function delete($rolID)
    {
        try {
            DB::beginTransaction();
            $rol = Rol::where('estado_registro', 'A')->find($rolID);
            if (!$rol) {
                return response()->json(['resp' => 'El Rol ya se encuentra Inhabilitados'], 200);
            }
            $rol->update([
                'estado_registro' => 'I'
            ]);
            DB::commit();
            return response()->json(['resp' => 'Rol inhabilitado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function asignarAcceso(Request $request, $rolID)
    {
        try {
            DB::beginTransaction();
            $rol = Rol::where('estado_registro', 'A')->find($rolID);
            if (!$rol) {
                return response()->json(['resp' => 'El Rol no esta disponible'], 200);
            }
            $accesos = $request->accesos;
            //eliminar los accesos que no se envia por el request
            AccesoRol::where('rol_id',$rol->id)->whereNotIn('acceso_id',$accesos)->delete();
            foreach ($accesos as $accesoId) {
                $buscar_acceso_disponibles = Acceso::find($accesoId);
                if ($buscar_acceso_disponibles) {
                    // Crea la relación en la tabla pivot (AccesoRol)
                    AccesoRol::updateOrcreate([
                        'rol_id' => $rolID,
                        'acceso_id' => $accesoId
                    ]);
                } else {
                    return response()->json(['error' => "Acceso ID $accesoId no encontrado"], 404);
                }
            }
            DB::commit();
            return response()->json(['resp' => 'Accesos asignados Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
