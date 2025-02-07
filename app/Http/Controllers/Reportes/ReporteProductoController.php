<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;

use App\Models\Articulo;
use App\Models\SubFamilia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReporteProductoController extends Controller
{
    /**
     * Genera un reporte filtrado por año y mes.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reporteFiltro(Request $request)
    {
        try {
            // Obtener el subfamilia_id del request
            $subFamiliaId = $request->sub_familia_id;

            // Obtener la subfamilia por ID
            $sub_familia = SubFamilia::where('estado_registro', 'A')
                ->where('id', $subFamiliaId)
                ->first();

            if (!$sub_familia) {
                return response()->json(['error' => 'Subfamilia no encontrada'], 500);
            }

            // Consultar artículos asociados a la subfamilia con estado activo
            $articulosQuery = Articulo::with(['producto.inventario', 'producto.transaccion.salida'])
                ->where('estado_registro', 'A')
                ->where('sub_familia_id', $sub_familia->id);

            // Obtener los artículos
            $articulos = $articulosQuery->get();

            if ($articulos->isEmpty()) {
                return response()->json(['error' => 'Artículos no encontrados'], 500);
            }

            // Inicializar variables para el cálculo
            $productosConTotales = [];
            $productoMayor = null;
            $productoMenor = null;
            $totalSalidasGeneral = 0;

            // Procesar artículos para calcular salidas
            foreach ($articulos as $articulo) {
                $salidasFiltradas = $articulo->producto->flatMap(function ($producto) {
                    return $producto->transaccion->flatMap(function ($transaccion) {
                        return $transaccion->salida->map(function ($salida) {
                            return [
                                'fecha' => $salida->fecha,
                                'numero_salida' => $salida->numero_salida
                            ];
                        });
                    });
                });

                $totalSalida = $salidasFiltradas->sum(function ($salida) {
                    return (int)$salida['numero_salida'];
                });

                // Acumulando el total general de salidas
                $totalSalidasGeneral += $totalSalida;

                $sku = $articulo->producto->first()->SKU ?? 'No disponible';

                $productoData = [
                    'id' => $articulo->id,
                    'nombre' => $articulo->nombre,
                    'SKU' => $sku,
                    'fecha_salida' => $salidasFiltradas,
                    'total_salida' => $totalSalida
                ];

                $productosConTotales[$articulo->id] = $productoData;

                // Determinar el producto con mayor y menor salida
                if ($productoMayor === null || $totalSalida > $productoMayor['total_salida']) {
                    $productoMayor = $productoData;
                }

                if ($productoMenor === null || $totalSalida < $productoMenor['total_salida']) {
                    $productoMenor = $productoData;
                }
            }

            // Devolver la respuesta en formato JSON
            return response()->json([
                'data' => [
                    'sub_familia' => $sub_familia->nombre, // Descripción de la subfamilia
                    'total_salidas_general' => $totalSalidasGeneral, // Total de salidas general
                    'productos' => array_values($productosConTotales) // Datos de los productos
                ],
                'producto_mayor' => $productoMayor ? [
                    'id' => $productoMayor['id'],
                    'nombre' => $productoMayor['nombre'],
                    'SKU' => $productoMayor['SKU'],
                    'total_salida' => $productoMayor['total_salida']
                ] : null,
                'producto_menor' => $productoMenor ? [
                    'id' => $productoMenor['id'],
                    'nombre' => $productoMenor['nombre'],
                    'SKU' => $productoMenor['SKU'],
                    'total_salida' => $productoMenor['total_salida']
                ] : null
            ], 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }



    public function reporteFiltroMes(Request $request)
    {
        try {
            // Obtener el año y subfamilia del request
            $año = $request->año;
            $subFamiliaId = $request->sub_familia_id;

            // Validar el año
            if (!$año || !is_numeric($año)) {
                return response()->json(['error' => 'Año no válido'], 400);
            }
            // Obtener la subfamilia por ID
            $sub_familia = SubFamilia::where('estado_registro', 'A')
                ->where('id', $subFamiliaId)
                ->first();

            if (!$sub_familia) {
                return response()->json(['error' => 'Subfamilia no encontrada'], 500);
            }

            // Consultar artículos asociados a la subfamilia con estado activo
            $articulos = Articulo::with(['producto.transaccion.salida'])
                ->where('sub_familia_id', $sub_familia->id)
                ->get();

            if ($articulos->isEmpty()) {
                return response()->json(['error' => 'Artículos no encontrados'], 500);
            }

            // Array con los nombres de los meses en español
            $mesesEnEspañol = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ];

            // Inicializar un array para organizar la salida por mes
            $respuestaPorMes = [];
            for ($mes = 1; $mes <= 12; $mes++) {
                $respuestaPorMes[$mes] = [
                    'mes' => $mesesEnEspañol[$mes],
                    'productos' => [],
                    'total_salida' => 0
                ];
            }

            // Organizar los artículos por mes de salida
            foreach ($articulos as $articulo) {
                foreach ($articulo->producto as $producto) {
                    foreach ($producto->transaccion as $transaccion) {
                        foreach ($transaccion->salida as $salida) {
                            $fecha = new \DateTime($salida->fecha);
                            $mes = $fecha->format('n'); // Obtiene el mes en formato numérico (1-12)
                            $añoSalida = $fecha->format('Y'); // Obtiene el año

                            // Filtrar por año
                            if ($añoSalida != $año) {
                                continue;
                            }

                            if (!isset($respuestaPorMes[$mes]['productos'][$articulo->id])) {
                                $respuestaPorMes[$mes]['productos'][$articulo->id] = [
                                    'id' => $articulo->id,
                                    'nombre' => $articulo->nombre,
                                    'SKU' => $producto->SKU,
                                    'salidas' => []
                                ];
                            }

                            $respuestaPorMes[$mes]['productos'][$articulo->id]['salidas'][] = [
                                'fecha_salida' => $salida->fecha,
                                'numero_salida' => $salida->numero_salida
                            ];

                            $respuestaPorMes[$mes]['total_salida'] += $salida->numero_salida;
                        }
                    }
                }
            }

            // Filtrar meses que no tienen productos
            $respuestaFinal = array_filter($respuestaPorMes, function ($datosMes) {
                return count($datosMes['productos']) > 0;
            });

            // Formatea la respuesta
            foreach ($respuestaFinal as $mes => &$datosMes) {
                $datosMes['productos'] = array_values($datosMes['productos']);
            }

            // Devolver la respuesta en formato JSON
            return response()->json(['data' => $respuestaFinal], 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
