<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\EstadoComprobante;
use Illuminate\Http\Request;

class EstadoComprobanteController extends Controller
{
    public function GetEstado() {
        try{
            $estadoComprobante = EstadoComprobante::all();
            if(!$estadoComprobante){
                return response()->json(['error' => 'No se encontraron estados de comprobantes'], 404);
            }
            return response()->json(['data' => $estadoComprobante], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
