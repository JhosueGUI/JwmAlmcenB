<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function getEmpresa() {
        try {
            $empresas = Empresa::where('estado_registro', 'A')->get();
            return response()->json(['data' => $empresas], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
