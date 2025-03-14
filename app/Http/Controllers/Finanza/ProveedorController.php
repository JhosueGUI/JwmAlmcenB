<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function create(Request $request){
        try{
            $proveedorExistente=Proveedor::where('estado_registro','A')->where('ruc',$request->ruc)->first();
            if($proveedorExistente){
                return response()->json(['resp'=>'El RUC ya está en uso por otro proveedor'],500);
            }
            Proveedor::updateOrCreate(
                [
                    'ruc'=>$request->ruc,
                ],
                [
                    'razon_social'=>$request->razon_social,
                    'direccion'=>$request->direccion,
                ]
            );
            return response()->json(['resp'=>'Proveedor creado correctamente'],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function get(){
        try{
            $proveedor=Proveedor::select('id','razon_social','ruc','direccion')->where('estado_registro','A')->get();
            return response()->json(['data'=>$proveedor],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
