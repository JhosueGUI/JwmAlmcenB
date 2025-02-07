<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\Articulo;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function get()
    {
        try {
            $producto = Producto::with('articulo','unidad_medida')->where('estado_registro', 'A')->get();
            if (!$producto) {
                return response()->json(['resp' => 'Productos no existentes'], 200);
            }
            return response()->json(['data' => $producto], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getProductoFiltro(){
        
    }
    public function show($productoID)
    {
        try {
            $producto = Producto::where('estado_registro', 'A')->find($productoID);
            if (!$producto) {
                return response()->json(['resp' => 'Producto no existente'], 200);
            }
            return response()->json(['resp' => $producto], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {

            $articulo = Articulo::create([
                'nombre' => $request->nombre,
                'sub_familia_id' => $request->sub_familia_id
            ]);
            $producto = Producto::create([
                'articulo_id' => $articulo->id,
                'SKU' => $request->SKU,
                'unidad_medida_id' => $request->unidad_medida_id,
                'ubicacion_id' => $request->ubicacion_id
            ]);
            DB::commit();
            return response()->json(['resp' => 'Producto Creado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $productoID)
    {
        try {
            DB::beginTransaction();
            $producto = Producto::where('estado_registro', 'A')->find($productoID);
            $articulo = Articulo::where('estado_registro', 'A')->find($producto->articulo_id);
            if (!$producto) {
                return response()->json(['resp' => 'Producto no existente'], 200);
            }
            $articulo->update([
                'nombre' => $request->nombre,
                'sub_familia_id' => $request->sub_familia_id
            ]);
            $producto->update([
                'SKU' => $request->SKU,
                'articulo_id' => $articulo->id,
                'unidad_medida_id' => $request->unidad_medida_id,
                'ubicacion_id' => $request->ubicacion_id
            ]);
            DB::commit();
            return response()->json(['resp' => 'Producto Actualizado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function delete($productoID)
    {
        try {
            DB::beginTransaction();
            $producto = Producto::where('estado_registro', 'A')->find($productoID);
            if (!$producto) {
                return response()->json(['resp' => 'Producto ya se encuentra Inhabilitado'], 200);
            }
            $producto->update([
                'estado_registro' => 'I'
            ]);
            DB::commit();
            return response()->json(['resp' => 'Producto Inhabilitado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getEstockLogico($idProducto)
    {
        try {
            $producto = Producto::where('estado_registro', 'A')->where('SKU',$idProducto)->first();
            if (!$producto) {
                return response()->json(['resp' => 'Producto No Existente'], 200);
            }
            $inventario = Inventario::where('estado_registro', 'A')->where('producto_id', $producto->id)->first();
            $stock_logico=$inventario->stock_logico;
            

            return response()->json(['data' => $stock_logico], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
