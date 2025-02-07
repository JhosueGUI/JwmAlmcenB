<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\SubFamilia;
use Illuminate\Http\Request;
class SubFamiliaController extends Controller
{
    public function get(){
        try {
            $familia=Familia::with('SubFamilia')->where('estado_registro','A')->get();
            if(!$familia){
                return response()->json(['resp'=>'No existen sub familias disponibles'],500);
            }
            return response()->json(['data'=>$familia],200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getSubFamilia(){
        try {
            $familia=SubFamilia::where('estado_registro','A')->get();
            if(!$familia){
                return response()->json(['resp'=>'No existen sub familias disponibles'],500);
            }
            return response()->json(['data'=>$familia],200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
