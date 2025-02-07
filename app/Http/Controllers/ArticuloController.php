<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function get(){
        try{
            $articulo=Articulo::with('sub_familia.familia')->where('estado_registro','A')->get();
            if(!$articulo){
                return response()->json(['resp'=>'Articulos no existentes'],200);
            }
            return response()->json(['data'=>$articulo],200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($articuloID){
        try{
            $articulo=Articulo::where('estado_registro','A')->find($articuloID);
            if(!$articulo){
                return response()->json(['resp'=>'Articulo no existente'],200);
            }
            return response()->json(['resp'=>$articulo],200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request){
        try{
            DB::beginTransaction();
            $articulo=Articulo::create([
                'nombre'=>$request->nombre,
                'sub_familia_id'=>$request->sub_familia_id
            ]);
            DB::commit();
            return response()->json(['resp'=>'Articulo creado correctamente'],200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update (Request $request, $articuloID){
        try{
            DB::beginTransaction();
            $articulo=Articulo::where('estado_registro','A')->find($articuloID);
            if(!$articulo){
                return response()->json(['resp'=>'Articulo no disponible']);
            }
            $articulo->update([
                'nombre'=>$request->nombre,
                'sub_familia_id'=>$request->sub_familia_id
            ]);
            DB::commit();
            return response()->json(['resp'=>'Articulo actualizado correctamente'],200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function delete($articuloID){
        try{
            DB::beginTransaction();
            $articulo=Articulo::where('estado_registro','A')->find($articuloID);
            if(!$articulo){
                return response()->json(['resp'=>'Articulo ya se encuentra inhabilitado']);
            }
            $articulo->update([
                'estado_registro'=>'I',
            ]);
            DB::commit();
            return response()->json(['resp'=>'Articulo inhabilitado correctamente'],200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
