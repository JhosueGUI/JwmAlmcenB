<?php

namespace App\Http\Controllers;

use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    public function get(){
        try {
            $tipo_documento=TipoDocumento::where('estado_registro','A')->get();
            if(!$tipo_documento){
                return response()->json(['resp'=>'Tipo Documentos No Existentes']);
            }
            return response()->json(['data' => $tipo_documento], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
