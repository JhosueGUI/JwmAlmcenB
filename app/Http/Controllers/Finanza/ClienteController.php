<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function getCliente()
    {
        try {
            $clientes = Cliente::where('estado_registro', 'A')->get();
            return response()->json(['data' => $clientes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $existeCliente=Cliente::where('nombre_cliente', $request->nombre_cliente)->first();
            if($existeCliente){
                return response()->json(['resp' => 'Cliente ya existente'], 200);
            }
            Cliente::create([
                'nombre_cliente' => $request->nombre_cliente,
            ]);
            DB::commit();
            return response()->json(['resp' => 'Cliente creado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
