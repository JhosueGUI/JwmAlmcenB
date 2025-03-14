<?php

namespace App\Http\Controllers\Finanza;

use App\Http\Controllers\Controller;
use App\Models\FINANZA\Categoria;

class SubCategoriaController extends Controller
{
    public function getSubCategoria(){
        try {
            $subcategorias = Categoria::with(['subcategoria'])->where('estado_registro', 'A')->get();
            return response()->json(['data' => $subcategorias], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
