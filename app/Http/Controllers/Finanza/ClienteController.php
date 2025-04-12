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
            $cliente=Cliente::create([
                'nombre_cliente' => $request->nombre_cliente,
            ]);
            if($request->nombre_cliente === $cliente->nombre_cliente){
                return response()->json(['resp' => 'Cliente ya existente'], 200);
            }
            DB::commit();
            return response()->json(['resp' => 'Cliente creado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
