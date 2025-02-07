<?php

namespace App\Http\Controllers;

use App\Models\DestinoCombustible;

class DestinoCombustibleController extends Controller
{
    public function getDestinoCombustible()
    {
        try {
            $destino_combustible = DestinoCombustible::where('estado_registro', 'A')->get();
            if (!$destino_combustible) {
                return response()->json(["resp" => "No se encontraron destinos de combustible"], 404);
            }
            return response()->json(['data' => $destino_combustible], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
}
