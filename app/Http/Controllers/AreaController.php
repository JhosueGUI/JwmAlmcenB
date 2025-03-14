<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\VIEW\VistaPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function get3()
    {
        try {
            $area = Area::where('estado_registro', 'A')->get();
            if (!$area) {
                return response()->json(['resp' => 'Areas no Disponibles']);
            }
            return response()->json(['data' => $area], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $area = Area::create([
                'nombre' => $request->nombre,
            ]);
            DB::commit();
            return response()->json(['resp','Area creado correctamente'],200);
        }catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function get(){
        $persona=VistaPersona::all();
        return response()->json(['data'=>$persona],200);
    }
}
