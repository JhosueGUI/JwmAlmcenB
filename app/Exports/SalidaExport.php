<?php

namespace App\Exports;

use App\Models\Salida;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalidaExport implements FromView
{
    /**
    * @return View
    */
    public function view(): View
{
    return view('excel.SalidaExport', [
        'Salidas' => Salida::with([
            'personal.persona',
            'transaccion.producto.articulo.sub_familia.familia',
            'transaccion.producto.unidad_medida',
            'transaccion.producto.inventario'
        ])->get(),
    ]);
}

}
