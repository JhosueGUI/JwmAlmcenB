<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\EstadoOperativo;
use App\Models\Ubicacion;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class RequerimientoController extends Controller
{
    public function getUnidad()
    {
        try {
            $unidad_medida = UnidadMedida::where('estado_registro', 'A')->get();
            if (!$unidad_medida) {
                return response()->json(['resp' => 'Unidad de Medidad No Existentes'], 200);
            }
            return response()->json(['data' => $unidad_medida], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getUbicacion()
    {
        try {
            $unidad_medida = Ubicacion::where('estado_registro', 'A')->get();
            if (!$unidad_medida) {
                return response()->json(['resp' => 'Ubicaciones No Existentes'], 200);
            }
            return response()->json(['data' => $unidad_medida], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getEstadoOperativo()
    {
        try {
            $unidad_medida = EstadoOperativo::where('estado_registro', 'A')->get();
            if (!$unidad_medida) {
                return response()->json(['resp' => 'Estados Operativos No Existentes'], 200);
            }
            return response()->json(['data' => $unidad_medida], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getAccesos()
    {
        try {
            $accesos = Acceso::with('sub_acceso.sub_acceso')
                ->where('estado_registro', 'A')
                ->whereNull('acceso_padre_id')
                ->get();

            if ($accesos->isEmpty()) {
                return response()->json(['resp' => 'Accesos No Existentes'], 200);
            }

            return response()->json(['data' => $accesos], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
