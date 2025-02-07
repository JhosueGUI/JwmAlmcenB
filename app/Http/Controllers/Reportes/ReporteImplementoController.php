<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Flota;
use App\Models\Salida;
use Illuminate\Http\Request;

class ReporteImplementoController extends Controller
{
    public function reporteFiltro(Request $request)
    {
        try {
            $unidad = $request->unidad;
            if (!$unidad) {
                return response()->json(['resp' => 'Unidad no Existente'], 500);
            }

            // Obtén todas las salidas con sus relaciones necesarias
            $salidas = Salida::with('transaccion.producto.articulo.sub_familia')
                ->where('unidad', $unidad)
                ->get();

            $respuesta = [
                'unidad' => $unidad,
                'total_general' => 0,  // Inicializa el total general
                'sub_familias' => [],
                'producto_mayor' => null,  // Inicializa el producto con mayor salidas
                'producto_menor' => null   // Inicializa el producto con menor salidas
            ];

            foreach ($salidas as $salida) {
                $subFamilia = $salida->transaccion->producto->articulo->sub_familia;
                $producto = $salida->transaccion->producto;

                if (!isset($respuesta['sub_familias'][$subFamilia->id])) {
                    $respuesta['sub_familias'][$subFamilia->id] = [
                        'id' => $subFamilia->id,
                        'nombre' => $subFamilia->nombre,
                        'total_salida' => 0,
                        'productos' => []
                    ];
                }

                if (!isset($respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU])) {
                    $respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU] = [
                        'SKU' => $producto->SKU,
                        'nombre' => $producto->articulo->nombre,
                        'total_salida_producto' => 0,  // Inicializa el total de salidas por producto
                        'salidas' => []
                    ];
                }

                $respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU]['salidas'][] = [
                    'id' => $salida->id,
                    'fecha' => $salida->fecha,
                    'numero_salida' => $salida->numero_salida
                ];

                // Incrementa el total de salidas por producto
                $respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU]['total_salida_producto'] += (int) $salida->numero_salida;
                // Incrementa el total de salidas por subfamilia
                $respuesta['sub_familias'][$subFamilia->id]['total_salida'] += (int) $salida->numero_salida;
                // Incrementa el total general de salidas
                $respuesta['total_general'] += (int) $salida->numero_salida;
            }

            $productoMayor = null;
            $productoMenor = null;

            foreach ($respuesta['sub_familias'] as $key => &$subFamilia) {
                $subFamilia['productos'] = array_values($subFamilia['productos']);

                foreach ($subFamilia['productos'] as $producto) {
                    if (!$productoMayor || $producto['total_salida_producto'] > $productoMayor['total_salida_producto']) {
                        $productoMayor = $producto;
                    }

                    if (!$productoMenor || $producto['total_salida_producto'] < $productoMenor['total_salida_producto']) {
                        $productoMenor = $producto;
                    }
                }
            }

            $respuesta['sub_familias'] = array_values($respuesta['sub_familias']);
            $respuesta['producto_mayor'] = $productoMayor;
            $respuesta['producto_menor'] = $productoMenor;

            return response()->json(['data' => $respuesta], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function reporteFiltroMes(Request $request)
    {
        try {
            $unidad = $request->unidad;
            $año = $request->año;

            if (!$unidad) {
                return response()->json(['resp' => 'Unidad no Existente'], 500);
            }

            // Obtén todas las salidas con sus relaciones necesarias y filtra por año si se proporciona
            $salidas = Salida::with('transaccion.producto.articulo.sub_familia')
                ->where('unidad', $unidad)
                ->when($año, function ($query) use ($año) {
                    $query->whereYear('fecha', $año);
                })
                ->get();

            $respuesta = [
                'unidad' => $unidad,
                'total_general' => 0,  // Inicializa el total general
                'sub_familias' => [],
                'producto_mayor' => null,  // Inicializa el producto con mayor salidas
                'producto_menor' => null   // Inicializa el producto con menor salidas
            ];

            foreach ($salidas as $salida) {
                $subFamilia = $salida->transaccion->producto->articulo->sub_familia;
                $producto = $salida->transaccion->producto;

                if (!isset($respuesta['sub_familias'][$subFamilia->id])) {
                    $respuesta['sub_familias'][$subFamilia->id] = [
                        'id' => $subFamilia->id,
                        'nombre' => $subFamilia->nombre,
                        'total_salida' => 0,
                        'productos' => []
                    ];
                }

                if (!isset($respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU])) {
                    $respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU] = [
                        'SKU' => $producto->SKU,
                        'nombre' => $producto->articulo->nombre,
                        'total_salida_producto' => 0,  // Inicializa el total de salidas por producto
                        'salidas' => []
                    ];
                }

                $respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU]['salidas'][] = [
                    'id' => $salida->id,
                    'fecha' => $salida->fecha,
                    'numero_salida' => $salida->numero_salida
                ];

                // Incrementa el total de salidas por producto
                $respuesta['sub_familias'][$subFamilia->id]['productos'][$producto->SKU]['total_salida_producto'] += (int) $salida->numero_salida;
                // Incrementa el total de salidas por subfamilia
                $respuesta['sub_familias'][$subFamilia->id]['total_salida'] += (int) $salida->numero_salida;
                // Incrementa el total general de salidas
                $respuesta['total_general'] += (int) $salida->numero_salida;
            }

            $productoMayor = null;
            $productoMenor = null;

            foreach ($respuesta['sub_familias'] as $key => &$subFamilia) {
                $subFamilia['productos'] = array_values($subFamilia['productos']);

                foreach ($subFamilia['productos'] as $producto) {
                    if (!$productoMayor || $producto['total_salida_producto'] > $productoMayor['total_salida_producto']) {
                        $productoMayor = $producto;
                    }

                    if (!$productoMenor || $producto['total_salida_producto'] < $productoMenor['total_salida_producto']) {
                        $productoMenor = $producto;
                    }
                }
            }

            $respuesta['sub_familias'] = array_values($respuesta['sub_familias']);
            $respuesta['producto_mayor'] = $productoMayor;
            $respuesta['producto_menor'] = $productoMenor;

            return response()->json(['data' => $respuesta], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
