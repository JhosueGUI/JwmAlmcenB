<?php

namespace App\Http\Controllers;

use App\Exports\ProveedorExport;
use App\Imports\ProveedorImport;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//para api
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProveedorController extends Controller
{
    public function get()
    {
        try {
            $proveedor = Proveedor::where('estado_registro', 'A')->get();
            if (!$proveedor) {
                return response()->json(['resp' => 'Proveedores no disponibles'], 500);
            }
            return response()->json(['data' => $proveedor], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($idProveedor)
    {
        try {
            $proveedor = Proveedor::where('estado_registro', 'A')->find($idProveedor);
            if (!$proveedor) {
                return response()->json(['resp' => 'Proveedor no disponibles'], 500);
            }
            return response()->json(['resp' => $proveedor], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $proveedor = Proveedor::updateOrCreate(
                [
                    'ruc' => $request->ruc,
                ],
                [
                    'razon_social' => $request->razon_social,
                    'direccion' => $request->direccion,
                    'forma_pago' => $request->forma_pago,
                    'contacto' => $request->contacto,
                    'numero_celular' => $request->numero_celular,
                    'estado_registro' => 'A'
                ]
            );
            DB::commit();
            return response()->json(['resp' => 'Proveedor Creado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $idProveedor)
    {
        try {
            DB::beginTransaction();
            $proveedor = Proveedor::where('estado_registro', 'A')->find($idProveedor);
            if (!$proveedor) {
                return response()->json(['resp' => 'Proveedor no disponibles'], 500);
            }
            $proveedor->update([
                'ruc' => $request->ruc,
                'razon_social' => $request->razon_social,
                'direccion' => $request->direccion,
                'forma_pago' => $request->forma_pago,
                'contacto' => $request->contacto,
                'numero_celular' => $request->numero_celular,
                'estado_registro' => 'A'
            ]);
            DB::commit();
            return response()->json(['resp' => 'Proveedor Actualizado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function ConsultasApiGet(Request $request)
    {
        try {
            $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Impob3N1ZWxlb3BvbGRvQGdtYWlsLmNvbSJ9.8LLwv5uVyFvZmyTy9PKpqOBwQ1qokBaXncI1FJYnD2Q";
            $ruc = $request->ruc;
            $url = "https://dniruc.apisperu.com/api/v1/ruc/{$ruc}?token={$token}";
            if (!$ruc) {
                return response()->json(['resp' => 'Ruc no ingresado']);
            }
            $cliente = new Client();
            $respuesta = $cliente->get($url);
            $dataConsulta = json_decode($respuesta->getBody()->getContents());
            return response()->json(['data' => $dataConsulta], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function delete($idProveedor)
    {
        try {
            DB::beginTransaction();
            $proveedor = Proveedor::where('estado_registro', 'A')->find($idProveedor);
            if (!$proveedor) {
                return response()->json(['resp' => 'El proveedor ya se encuentra Inhabilitado'], 500);
            }
            $proveedor->update([
                'estado_registro' => 'I'
            ]);
            DB::commit();
            return response()->json(['resp' => 'Proveedor Inhabilitado Correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function exportarProveedor()
    {
        return Excel::download(new ProveedorExport, 'Proveedor.xlsx');
    }

    public function subirProveedor(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'proveedor_excel' => 'required|file|mimes:xlsx,xls'
            ]);

            $archivo = $request->file('proveedor_excel');

            $nombreArchivo = $archivo->getClientOriginalName();

            if (Storage::exists('public/proveedor/' . $nombreArchivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }

            $archivo->storeAs('public/proveedor', $nombreArchivo);

            Excel::import(new ProveedorImport, storage_path('app/public/proveedor/' . $nombreArchivo));
            DB::commit();
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
