<?php

namespace App\Http\Controllers;

use App\Models\RRHH\Planilla;
use Illuminate\Http\Request;

class PlanillaController extends Controller
{
    public function get()
    {
        try {
            $planilla = Planilla::all();
            if (!$planilla) {
                return response()->json(['message' => 'No se encontraron planillas'], 404);
            }
            return response()->json(['data' => $planilla], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
