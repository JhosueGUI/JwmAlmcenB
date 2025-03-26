<?php

namespace App\Http\Controllers\Combustible;

use App\Http\Controllers\Controller;
use App\Models\COMBUSTIBLE\Grifo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrifoController extends Controller
{
    public function get()
    {
        try {
            $grifo = Grifo::where('estado_registro', 'A')->get();
            if (!$grifo) {
                return response()->json('No se encontraron registros');
            }
            return response()->json(['data' => $grifo], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $grifoExistente = Grifo::where('estado_registro', 'A')->where('ruc', $request->ruc)->first();
            if($grifoExistente) {
                return response()->json(['resp' => 'El grifo ya existe'], 500);
            }
            Grifo::create([
                'ruc' => $request->ruc,
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
            ]);
            DB::commit();
            return response()->json(['resp' => 'Grifo creado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
