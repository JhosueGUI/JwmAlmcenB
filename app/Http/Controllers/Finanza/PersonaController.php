<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $existePersona = Persona::where('estado_registro', 'A')->where('numero_documento', $request->numero_documento)->first();
            if ($existePersona) {
                return response()->json(['resp' => 'El número de documento ya está en uso por otra persona'], 500);
            }
            Persona::updateOrCreate([
                'numero_documento' => $request->numero_documento,
            ], [
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'tipo_documento_id' => 1,
            ]);
            DB::commit();
            return response()->json(['resp' => 'Persona creada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function get(){
        try{
            $persona=Persona::where('estado_registro', 'A')->get();
            return response()->json(['data'=>$persona],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
