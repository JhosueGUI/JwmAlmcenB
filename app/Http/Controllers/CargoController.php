<?php

namespace App\Http\Controllers;

use App\Models\RRHH\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function get()
    {
        try {
            $cargo = Cargo::where('estado_registro', 'A')->get();
            if (!$cargo) {
                return response()->json('No se encontraron registros');
            }
            return response()->json(['data' => $cargo], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
