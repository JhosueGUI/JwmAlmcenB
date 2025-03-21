<?php

namespace App\Http\Controllers;

use App\Exports\InventarioExport;
use App\Imports\InventarioImport;
use App\Models\Articulo;
use App\Models\Ingreso;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Producto;
use App\Models\Transaccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InventarioValorizadoController extends Controller
{
    public function get(Request $request)
    {
        try {
            // $perPage = $request->input('per_page', 50);

            // Obtener los inventarios valorizados con relaciones optimizadas
            $inventario_valorizado = InventarioValorizado::with([
                'inventario' => function ($query) {
                    $query->select('id', 'ubicacion_id', 'estado_operativo_id', 'producto_id', 'total_ingreso', 'total_salida', 'stock_logico', 'demanda_mensual');
                },
                'inventario.ubicacion:id,codigo_ubicacion',
                'inventario.estado_operativo:id,nombre',
                'inventario.producto' => function ($query) {
                    $query->select('id', 'articulo_id', 'unidad_medida_id', 'SKU');
                },
                'inventario.producto.articulo' => function ($query) {
                    $query->select('id', 'sub_familia_id', 'nombre', 'precio_soles', 'precio_dolares');
                },
                'inventario.producto.articulo.sub_familia' => function ($query) {
                    $query->select('id', 'familia_id', 'nombre');
                },
                'inventario.producto.articulo.sub_familia.familia:id,familia',
                'inventario.producto.unidad_medida:id,nombre',
                'inventario.producto.transaccion' => function ($query) {
                    $query->whereHas('ingreso')
                        ->orWhereHas('salida')
                        ->with(['ingreso:id,transaccion_id,fecha', 'salida:id,transaccion_id:fecha'])
                        ->latest();
                }
            ])
                ->where('estado_registro', 'A')->get();
            // ->paginate($perPage);

            // Verificar si hay datos
            if ($inventario_valorizado->isEmpty()) {
                return response()->json(['resp' => 'Inventarios Valorizados no existentes'], 500);
            }

            return response()->json(['data' => $inventario_valorizado], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($inventario_valorizadoID)
    {
        try {
            $inventario_valorizado = InventarioValorizado::with([
                'inventario.producto.articulo',
                'inventario.producto.unidad_medida',
                'inventario.producto.ubicacion',
                'inventario.estado_operativo',
                'transaccion.producto.articulo',
                'transaccion.producto.unidad_medida',
                'transaccion.producto.ubicacion'
            ])->where('estado_registro', 'A')->find($inventario_valorizadoID);
            if (!$inventario_valorizado) {
                return response()->json(['resp' => 'Inventario Valorizado no existente'], 200);
            }
            return response()->json(['resp' => $inventario_valorizado], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            // Validación de campos obligatorios
            if (empty($request->ubicacion_id)) {
                return response()->json(['resp' => 'Ubicación no seleccionada'], 500);
            }
            if (empty($request->SKU)) {
                return response()->json(['resp' => 'SKU no ingresado, o no valido'], 500);
            }

            if (empty($request->sub_familia_id)) {
                return response()->json(['resp' => 'Familia y Sub Familia no seleccionados'], 500);
            }
            if (empty($request->nombre)) {
                return response()->json(['resp' => 'Nombre del Producto no Ingresado'], 500);
            }
            if (empty($request->unidad_medida_id)) {
                return response()->json(['resp' => 'Unidad de Medida no seleccionada'], 500);
            }
            $articulo = Articulo::create([
                'nombre' => $request->nombre,
                'sub_familia_id' => $request->sub_familia_id
            ]);
            $sku_existente = Producto::where('estado_registro', 'A')->where('SKU', $request->SKU)->first();
            if ($sku_existente) {
                return response()->json(['resp' => 'El SKU ingresado ya existe, intentalo nuevamente'], 500);
            }
            $producto = Producto::create([
                'articulo_id' => $articulo->id,
                'SKU' => $request->SKU,
                'unidad_medida_id' => $request->unidad_medida_id
            ]);
            $inventario = Inventario::create([
                'ubicacion_id' => $request->ubicacion_id,
                'producto_id' => $producto->id
            ]);
            $inventario_valorizado = InventarioValorizado::create([
                'inventario_id' => $inventario->id
            ]);
            // return response()->json($inventario_valorizado);
            DB::commit();
            return response()->json(['resp' => 'Inventario Valorizado creado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $inventario_valorizadoID)
    {
        try {
            DB::beginTransaction();

            // Validación de campos obligatorios
            $camposObligatorios = [
                'ubicacion_id' => 'Ubicación no seleccionada',
                'SKU' => 'SKU no ingresado, o no válido',
                'sub_familia_id' => 'Familia y Sub Familia no seleccionados',
                'nombre' => 'Nombre del Producto no ingresado',
                'unidad_medida_id' => 'Unidad de Medida no seleccionada'
            ];

            foreach ($camposObligatorios as $campo => $mensaje) {
                if (empty($request->$campo)) {
                    return response()->json(['resp' => $mensaje], 500);
                }
            }

            // Validación y actualización de Inventario Valorizado
            $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')
                ->find($inventario_valorizadoID);

            if (!$inventario_valorizado) {
                return response()->json(['resp' => 'Inventario Valorizado No Existente'], 500);
            }

            // Validación y actualización de Inventario
            $inventario = Inventario::find($inventario_valorizado->inventario_id);
            if (!$inventario) {
                return response()->json(['resp' => 'Inventario No Existente'], 500);
            }

            // Validación y actualización de Producto
            $producto = Producto::find($inventario->producto_id);
            if (!$producto) {
                return response()->json(['resp' => 'Producto No Existente'], 500);
            }

            // Validación y actualización de Artículo
            $articulo = Articulo::find($producto->articulo_id);
            if (!$articulo) {
                return response()->json(['resp' => 'Artículo No Existente'], 500);
            }

            // Validación de SKU existente
            $sku_existente = Producto::where('estado_registro', 'A')
                ->where('SKU', $request->SKU)
                ->where('id', '!=', $producto->id)
                ->first();
            if ($sku_existente) {
                return response()->json(['resp' => 'El SKU ingresado ya existe, intentalo nuevamente'], 500);
            }

            // Actualización de Artículo, Producto
            $inventario->update([
                'ubicacion_id' => $request->ubicacion_id
            ]);
            $articulo->update([
                'nombre' => $request->nombre,
                'sub_familia_id' => $request->sub_familia_id
            ]);

            $producto->update([
                'articulo_id' => $articulo->id,
                'SKU' => $request->SKU,
                'unidad_medida_id' => $request->unidad_medida_id
            ]);

            DB::commit();
            return response()->json(['resp' => 'Inventario Valorizado actualizado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Algo salió mal', 'message' => $e->getMessage()], 500);
        }
    }



    public function delete($inventario_valorizadoID)
    {
        try {
            DB::beginTransaction();
            $inventario_valorizado = InventarioValorizado::where('estado_registro', 'A')->find($inventario_valorizadoID);
            if (!$inventario_valorizado) {
                return response()->json(['resp' => 'Inventario ya se encuenta inhabilitado'], 200);
            }
            $inventario_valorizado->update([
                'estado_registro' => 'I'
            ]);
            DB::commit();
            return response()->json(['resp' => 'Inventario Valorizado inhabilitado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function importarInventario(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'inventario_excel' => 'required|file|mimes:xlsx,xls'
            ]);
            $archivo = $request->file('inventario_excel');
            $nombre_archivo = $archivo->getClientOriginalName();
            if (Storage::exists('public/inventario/' . $nombre_archivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }
            $archivo->storeAs('public/inventario', $nombre_archivo);
            Excel::import(new InventarioImport, storage_path('app/public/inventario/' . $nombre_archivo));
            DB::commit();
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function exportarInventario()
    {
        return Excel::download(new InventarioExport, 'Iventario.xlsx');
    }

    public function ObtenerUltimoSku()
    {
        $producto = Producto::where('estado_registro', 'A')->orderBy('id', 'desc')->first();
        if (!$producto) {
            return response()->json(['resp' => 1], 200);
        }
        $ultimo_sku = $producto->SKU;
        $ultimo_sku++;
        return response()->json(['resp' => $ultimo_sku], 200);
    }
}
