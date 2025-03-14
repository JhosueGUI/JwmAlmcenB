<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\Rendicion;

class RendicionController extends Controller
{
    public  function getRendicion(){
        try{
            $rendicion = Rendicion::all();
            if(!$rendicion){
                return response()->json(['error' => 'No se encontraron rendiciones'], 404);
            }
            return response()->json(['data' => $rendicion], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
