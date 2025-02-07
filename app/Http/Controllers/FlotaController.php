<?php

namespace App\Http\Controllers;

use App\Exports\FlotaExport;
use App\Imports\FlotaImport;
use App\Models\Flota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FlotaController extends Controller
{
    public function get()
    {
        try {
            $flota = Flota::with('personal.persona')->where('estado_registro', 'A')->get();
            if (!$flota) {
                return response()->json(['No hay flotas Disponibles'], 500);
            }
            return response()->json(['data' => $flota], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function getUnidad()
    {
        try {
            $unidad = Flota::where('estado_registro', 'A')->select('id','placa')->get();
            if (!$unidad) {
                return response()->json(['No hay Unidades Disponibles'], 500);
            }
            return response()->json(['data' => $unidad], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function show($idFlota)
    {
        try {
            $flota = Flota::where('estado_registro', 'A')->find($idFlota);
            if (!$flota) {
                return response()->json(['No hay flota Disponible'], 500);
            }
            return response()->json(['resp' => $flota], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $placaExistente = Flota::where('estado_registro', 'A')->where('placa', $request->placa)->first();
            if ($placaExistente) {
                return response()->json(["resp" => 'Número de placa ya registrado'], 500);
            }
            if (!$request->placa) {
                return response()->json(['resp' => 'Ingrese un número de Placa'], 500);
            }
            $flota = Flota::create([
                'placa' => $request->placa,
                'personal_id' => $request->personal_id,
                'tipo' => $request->tipo,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'empresa' => $request->empresa,
            ]);
            DB::commit();
            return response()->json(['resp' => 'Flota creado Correctamente'], 200);
            return response()->json(['resp' => $flota], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $idFlota)
    {
        try {
            DB::beginTransaction();
            $flota = Flota::where('estado_registro', 'A')->find($idFlota);
            if (!$flota) {
                return response()->json(['resp' => 'Flota no disponible'], 500);
            }
            $placaExistente = Flota::where('estado_registro', 'A')->where('placa', $request->placa)->where('id', '!=', $flota->id)->first();
            if ($placaExistente) {
                return response()->json(["resp" => 'Número de placa ya registrado'], 500);
            }
            $flota->update([
                'placa' => $request->placa,
                'personal_id' => $request->personal_id,
                'tipo' => $request->tipo,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'empresa' => $request->empresa,
            ]);
            DB::commit();
            return response()->json(['resp' => 'Flota Actualizado Correctamente'], 200);
            return response()->json(['resp' => $flota], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function delete($idFlota)
    {
        try {
            $flota = Flota::where('estado_registro', 'A')->find($idFlota);
            if (!$flota) {
                return response()->json(['La Flota ya se encuentra Inhabilitado'], 500);
            }
            $flota->update([
                'estado_registro' => 'I'
            ]);
            return response()->json(['resp' => 'Flota inhabilitado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function exportarFlota()
    {
        return Excel::download(new FlotaExport, 'Flota.xlsx');
    }
    public function importarFlota(Request $request)
    {
        try {
            $request->validate([
                'flota_excel' => 'required|file|mimes:xlsx,xls'
            ]);
            $archivo = $request->file('flota_excel');
            $nombreArchivo = $archivo->getClientOriginalName();

            // Verificar si el archivo ya existe en el almacenamiento
            if (Storage::exists('public/flota/' . $nombreArchivo)) {
                return response()->json(['error' => 'Ya existe un archivo con este nombre. Por favor, renombre el archivo y vuelva a intentarlo.'], 500);
            }

            // Importar el archivo usando la clase de importación
            $archivo->storeAs('public/flota', $nombreArchivo);

            Excel::import(new FlotaImport, storage_path('app/public/flota/' . $nombreArchivo));
            // Retornar una respuesta de éxito
            return response()->json(['resp' => 'Archivo subido y procesado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
