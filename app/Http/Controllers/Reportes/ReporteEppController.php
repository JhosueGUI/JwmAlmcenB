<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Personal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteEppController extends Controller
{
    public function reporteFiltro(Request $request)
    {
        try {
            $personal_request = $request->personal_id;
            $personal = Personal::with(
                ['salida.transaccion' => function ($query) {
                    $query->select('id', 'producto_id');
                }],
                ['salida.transaccion.producto.articulo' => function ($query) {
                    $query->select('id', 'nombre', 'sku');
                }],
                ['persona' => function ($query) {
                    $query->select('id', 'nombre', 'apellido_paterno');
                }]
            )
                ->where('id', $personal_request)
                ->select('id', 'persona_id', 'area_id', 'estado_registro')
                ->first();

            if (!$personal) {
                return response()->json(['resp' => 'Personal no Existente'], 500);
            }

            // Agrupa las salidas por producto y calcula la suma de salidas para cada producto
            $salidas = $personal->salida;
            $productos = [];
            $productoMayor = null;
            $productoMenor = null;

            foreach ($salidas as $salida) {
                $productoId = $salida->transaccion->producto_id;
                if (!isset($productos[$productoId])) {
                    $productos[$productoId] = [
                        'producto_id' => $salida->transaccion->producto->id,
                        'producto' => $salida->transaccion->producto->articulo->nombre,
                        'SKU' => $salida->transaccion->producto->SKU,
                        'salidas' => []
                    ];
                }
                $productos[$productoId]['salidas'][] = [
                    'fecha' => $salida->fecha,
                    'numero_salida' => $salida->numero_salida
                ];
            }

            // Calcula el total de salidas por producto y encuentra el producto con mayor y menor salida
            $productosConTotales = [];
            foreach ($productos as $productoId => $data) {
                $totalSalida = array_sum(array_column($data['salidas'], 'numero_salida'));
                $productosConTotales[$productoId] = [
                    'producto_id' => $data['producto_id'],
                    'producto' => $data['producto'],
                    'SKU' => $data['SKU'],
                    'total_salida' => $totalSalida,
                    'salidas' => $data['salidas']
                ];

                if ($productoMayor === null || $totalSalida > $productoMayor['total_salida']) {
                    $productoMayor = $productosConTotales[$productoId];
                }

                if ($productoMenor === null || $totalSalida < $productoMenor['total_salida']) {
                    $productoMenor = $productosConTotales[$productoId];
                }
            }

            // Ya no se necesita obtener el número de salida máximo o mínimo, simplemente usamos total_salida
            $respuesta = [
                'id' => $personal->id,
                'personal_id' => $personal->id,
                'personal' => $personal->persona->nombre . ' ' . $personal->persona->apellido_paterno,
                'producto_mayor' => $productoMayor ? [
                    'id' => $productoMayor['producto_id'],
                    'nombre' => $productoMayor['producto'],
                    'SKU' => $productoMayor['SKU'],
                    'total_salida' => $productoMayor['total_salida']
                ] : null,
                'producto_menor' => $productoMenor ? [
                    'id' => $productoMenor['producto_id'],
                    'nombre' => $productoMenor['producto'],
                    'SKU' => $productoMenor['SKU'],
                    'total_salida' => $productoMenor['total_salida']
                ] : null,
                'productos' => array_values($productosConTotales),
                'total_salida' => array_sum(array_column($productosConTotales, 'total_salida')),
            ];

            return response()->json(['data' => $respuesta], 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function reporteFiltroMes(Request $request)
    {
        try {
            $personal_request = $request->personal_id;
            $año = $request->año;
            // Validar el año
            if (!$año || !is_numeric($año)) {
                return response()->json(['error' => 'Año no válido'], 400);
            }
            $personal = Personal::with(
                ['salida.transaccion' => function ($query) {
                    $query->select('id', 'producto_id');
                }],
                ['salida.transaccion.producto.articulo' => function ($query) {
                    $query->select('id', 'nombre', 'sku');
                }],
                ['persona' => function ($query) {
                    $query->select('id', 'nombre', 'apellido_paterno');
                }]
            )
                ->where('id', $personal_request)
                ->select('id', 'persona_id', 'area_id', 'estado_registro')
                ->first();
    
            if (!$personal) {
                return response()->json(['resp' => 'Personal no Existente'], 500);
            }
    
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
    
            // Agrupa las salidas por mes y producto considerando el año
            foreach ($personal->salida as $salida) {
                $fecha = new \DateTime($salida->fecha);
                $mes = $fecha->format('n'); // Obtiene el mes en formato numérico (1-12)
                $añoSalida = $fecha->format('Y'); // Obtiene el año
    
                // Filtrar por año
                if ($añoSalida != $año) {
                    continue;
                }
    
                $productoId = $salida->transaccion->producto_id;
                $producto = $salida->transaccion->producto;
                $articulo = $producto->articulo;
    
                if (!isset($respuestaPorMes[$mes])) {
                    $respuestaPorMes[$mes] = [
                        'mes' => $mesesEnEspañol[$mes],
                        'productos' => [],
                        'total_salida' => 0
                    ];
                }
    
                if (!isset($respuestaPorMes[$mes]['productos'][$productoId])) {
                    $respuestaPorMes[$mes]['productos'][$productoId] = [
                        'id' => $producto->id,
                        'nombre' => $articulo->nombre,
                        'SKU' => $producto->SKU,
                        'salidas' => []
                    ];
                }
    
                $respuestaPorMes[$mes]['productos'][$productoId]['salidas'][] = [
                    'fecha_salida' => $salida->fecha,
                    'numero_salida' => $salida->numero_salida
                ];
    
                $respuestaPorMes[$mes]['total_salida'] += $salida->numero_salida;
            }
    
            // Filtrar meses que no tienen productos
            $respuestaFinal = array_filter($respuestaPorMes, function ($datosMes) {
                return count($datosMes['productos']) > 0;
            });
    
            // Formatea la respuesta
            foreach ($respuestaFinal as $mes => &$datosMes) {
                $datosMes['productos'] = array_values($datosMes['productos']);
            }
    
            return response()->json(['data' => $respuestaFinal], 200);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }
    
    public function reporteFiltroMes2(Request $request)
    {
        try {
            $personal_request = $request->personal_id;
            $año_request = $request->año;
            // Validar el año
            if (!$año_request || !is_numeric($año_request)) {
                return response()->json(['error' => 'Año no válido'], 400);
            }
            // Inicializar las fechas de inicio y fin para el año dado

            $fechaInicio = Carbon::create($año_request, 1, 1)->startOfYear();
            $fechaFin = Carbon::create($año_request, 12, 31)->endOfYear();

            // Obtener el personal por ID
            $personal = Personal::with(
                ['salida.transaccion' => function ($query) {
                    $query->select('id', 'producto_id');
                }],
                ['salida.transaccion.producto.articulo' => function ($query) {
                    $query->select('id', 'nombre', 'sku');
                }],
                ['persona' => function ($query) {
                    $query->select('id', 'nombre', 'apellido_paterno');
                }]
            )
                ->where('id', $personal_request)
                ->select('id', 'persona_id', 'area_id', 'estado_registro')
                ->first();

            if (!$personal) {
                return response()->json(['resp' => 'Personal no encontrada'], 500);
            }
            $personal->whereHas();

            return response()->json($personal);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Algo salió mal', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
