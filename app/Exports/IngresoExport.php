<?php

namespace App\Exports;

use App\Models\Ingreso;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IngresoExport implements FromView
{
    /**
     * @return View
     */
    public function view(): View
    {
        $ingresos = Ingreso::with([
            'transaccion.producto.articulo.sub_familia.familia',
            'transaccion.producto.unidad_medida',
            'transaccion.producto.inventario',
            'transaccion.producto.proveedor_producto.proveedor',
        ])->where('estado_registro', 'A')->get();
        // Recorrer cada ingreso y ajustar la estructura de proveedor_producto
        foreach ($ingresos as $ingreso) {
            $transaccion = $ingreso->transaccion;
            // Filtrar proveedor_producto por identificador
            $proveedor_producto = $transaccion->producto->proveedor_producto
                ->where('identificador', $transaccion->id)->first();
            // Agregar proveedor_producto al objeto transaccion
            $transaccion->proveedor_producto = $proveedor_producto;
            $transaccion->producto->makeHidden(['proveedor_producto']);

        }

        return view('excel.IngresoExport', [
            'Ingresos' => $ingresos,
        ]);
    }
}
