<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function getEmpresa()
    {
        try {
            $empresas = Empresa::where('estado_registro', 'A')->get();
            return response()->json(['data' => $empresas], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmpresaCamionero()
    {
        try {
            $empresa = Empresa::where('estado_registro', 'A')->where('nombre_empresa', 'CAJA CAMIONERO')->first();
            return response()->json(['resp' => $empresa], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmpresaFSJ()
    {
        try {
            $empresa = Empresa::where('estado_registro', 'A')->where('nombre_empresa', 'CAJA FSJ')->first();
            return response()->json(['resp' => $empresa], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmpresaPampaya()
    {
        try {
            $empresa = Empresa::where('estado_registro', 'A')->where('nombre_empresa', 'CAJA PAMPAYA')->first();
            return response()->json(['resp' => $empresa], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmpresaWilliam()
    {
        try {
            $empresa = Empresa::where('estado_registro', 'A')->where('nombre_empresa', 'CAJA WILLIAM')->first();
            return response()->json(['resp' => $empresa], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmpresaJoel()
    {
        try {
            $empresa = Empresa::where('estado_registro', 'A')->where('nombre_empresa', 'CAJA JOEL')->first();
            return response()->json(['resp' => $empresa], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmpresaJWM()
    {
        try {
            $empresa = Empresa::where('estado_registro', 'A')->where('nombre_empresa', 'CAJA JWM')->first();
            return response()->json(['resp' => $empresa], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
