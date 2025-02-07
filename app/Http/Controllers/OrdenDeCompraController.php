<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\OrdenCompra;
use App\Models\OrdenProducto;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdenDeCompraController extends Controller
{
    public function generarOrden(Request $request)
    {
        try {
            DB::beginTransaction();

            $orden_compra = OrdenCompra::where('estado_registro', 'A')->orderBy('id', 'desc')->first();
            //Obtener el ultimo numero de orden de compra
            $orden_compra_funcion = $this->GetUltimaOrdenCompra()->getData();
            $orden_compra_numero = $orden_compra_funcion->resp;

            $fecha = Carbon::now('America/Lima')->format('Y-m-d');
            $proveedor_id = $request->proveedor_id;
            $proveedor = Proveedor::where('estado_registro', 'A')->find($proveedor_id);

            // Validación de proveedor
            if (!$proveedor) {
                return response()->json(['resp' => 'Selecciona un Proveedor'], 500);
            }

            // Datos de la orden
            $requerimiento = $request->requerimiento;
            $gestor_compra = $request->gestor_compra;
            $solicitante = $request->solicitante;
            $detalle = $request->detalle;
            $cotizacion = $request->cotizacion;
            $productos = $request->productos;

            $data = [
                'orden_compra' => $orden_compra_numero,
                'fecha' => $fecha,
                'requerimiento' => $requerimiento,
                'gestor_compra' => $gestor_compra,
                'solicitante' => $solicitante,
                'detalle' => $detalle,
                'cotizacion' => $cotizacion,
                'proveedor' => $proveedor->razon_social ?? '',
                'ruc' => $proveedor->ruc ?? '',
                'tratado_con' => $proveedor->contacto ?? '',
                'numero_celular' => $proveedor->numero_celular ?? '',
                'direccion' => $proveedor->direccion ?? '',
                'forma_pago' => $proveedor->forma_pago ?? '',
                'productos' => [],
                'sub_total' => 0,
                'total_productos' => 0,
                'moneda' => null,
            ];

            // Crear o actualizar la orden de compra
            $orden_compra = OrdenCompra::updateOrCreate(
                ['numero_orden' => $orden_compra_numero],
                [
                    'fecha' => $fecha,
                    'proveedor_id' => $proveedor_id,
                    'requerimiento' => $requerimiento,
                    'gestor_compra' => $gestor_compra,
                    'solicitante' => $solicitante,
                    'detalle' => $detalle,
                    'cotizacion' => $cotizacion,
                ]
            );

            foreach ($productos as $productoData) {
                $SKU = $productoData['SKU'];
                $producto = Producto::where('estado_registro', 'A')->where('SKU', $SKU)->first();
                $cantidad = (float)$productoData['cantidad'];

                if ($producto) {
                    $precio_soles = isset($productoData['precio_soles']) ? (float)$productoData['precio_soles'] : null;
                    $precio_dolares = isset($productoData['precio_dolares']) ? (float)$productoData['precio_dolares'] : null;

                    if ($precio_soles !== null) {
                        $data['moneda'] = 'S/';
                        $data['productos'][] = [
                            'producto' => $producto->articulo->nombre,
                            'unidad' => $producto->unidad_medida->nombre,
                            'cantidad' => $cantidad,
                            'precio' => $precio_soles,

                            'total' => $precio_soles * $cantidad,
                        ];
                        $data['total_productos'] += $precio_soles * $cantidad;
                        $totalEnLetras = strtoupper($this->convertirNumeroEnLetras($data['total_productos'], 'Soles'));
                    } else if ($precio_dolares !== null) {
                        $data['moneda'] = '$/';
                        $data['productos'][] = [
                            'producto' => $producto->articulo->nombre,
                            'unidad' => $producto->unidad_medida->nombre,
                            'cantidad' => $cantidad,
                            'precio' => $precio_dolares,
                            'total' => $precio_dolares * $cantidad,
                        ];
                        $data['total_productos'] += $precio_dolares * $cantidad;
                        $totalEnLetras = strtoupper($this->convertirNumeroEnLetras($data['total_productos'], 'Dólares'));
                    }

                    OrdenProducto::updateOrCreate(
                        [
                            'orden_compra_id' => $orden_compra->id,
                            'producto_id' => $producto->id
                        ],
                        [
                            'cantidad' => $cantidad,
                            'precio_soles' => $precio_soles,
                            'precio_dolares' => $precio_dolares
                        ]
                    );
                }
            }


            // Calcular el subtotal
            $subTotal = (float) $data['total_productos'] / 1.18;
            $data['sub_total'] = number_format($subTotal, 2);

            // Calcular IGV (18%)
            $data['igv'] = number_format($subTotal * 0.18, 2);

            // Calcular el total
            $data['total'] = number_format($data['total_productos'], 2);

            $orden_compra->update([
                'sub_total' => $subTotal,
                'IGV' => $data['igv'],
                'total' => $data['total'],
            ]);



            // Renderiza la vista con los datos
            $html = view('pdf.ejemplo', [
                'data' => $data,
                'totalEnLetras' => $totalEnLetras
            ])->render();

            // Genera el PDF
            $pdf = Pdf::loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $output = $pdf->output();

            // Guarda el PDF en almacenamiento público
            $pdf_nombre = $orden_compra_numero . '-orden_compra.pdf';
            Storage::disk('public')->put("orden_compra/pdf/" . $pdf_nombre, $output);

            // Commit de la transacción
            DB::commit();

            // Devuelve la URL del archivo generado para descargar
            $ruta_archivo = asset("storage/orden_compra/pdf/" . $pdf_nombre);

            // Actualiza la URL del PDF en la base de datos
            $orden_compra->update(['url_pdf' => $ruta_archivo]);

            return response()->json(["resp" => "Orden de Compra Registrada correctamente", "url" => $ruta_archivo], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }

    public function editarOrdenCompra(Request $request, $ordenId)
    {
        try {
            DB::beginTransaction();

            // Buscar la orden de compra por ID
            $orden_compra = OrdenCompra::where('estado_registro', 'A')->find($ordenId);

            if (!$orden_compra) {
                return response()->json(['error' => 'Orden de Compra no existente'], 404);
            }

            $orden_compra_numero = $orden_compra->numero_orden;
            $fecha = Carbon::now('America/Lima')->format('Y-m-d');
            $proveedor_id = $request->proveedor_id;
            $proveedor = Proveedor::where('estado_registro', 'A')->find($proveedor_id);

            // Validación de proveedor
            if (!$proveedor) {
                return response()->json(['error' => 'Proveedor no válido'], 400);
            }

            // Datos de la orden
            $requerimiento = $request->requerimiento;
            $gestor_compra = $request->gestor_compra;
            $solicitante = $request->solicitante;
            $detalle = $request->detalle;
            $cotizacion = $request->cotizacion;
            $productos = $request->productos;

            $data = [
                'orden_compra' => $orden_compra_numero,
                'fecha' => $fecha,
                'requerimiento' => $requerimiento,
                'gestor_compra' => $gestor_compra,
                'solicitante' => $solicitante,
                'detalle' => $detalle,
                'cotizacion' => $cotizacion,
                'proveedor' => $proveedor->razon_social ?? '',
                'ruc' => $proveedor->ruc ?? '',
                'tratado_con' => $proveedor->contacto ?? '',
                'numero_celular' => $proveedor->numero_celular ?? '',
                'direccion' => $proveedor->direccion ?? '',
                'forma_pago' => $proveedor->forma_pago ?? '',
                'productos' => [],
                'sub_total' => 0,
                'total_productos' => 0,
                'moneda' => null,
            ];

            // Actualizar la orden de compra
            $orden_compra->update([
                'fecha' => $fecha,
                'proveedor_id' => $proveedor_id,
                'requerimiento' => $requerimiento,
                'gestor_compra' => $gestor_compra,
                'solicitante' => $solicitante,
                'detalle' => $detalle,
                'cotizacion' => $cotizacion,
            ]);

            // Eliminar productos antiguos
            OrdenProducto::where('orden_compra_id', $orden_compra->id)->delete();

            foreach ($productos as $productoData) {
                $SKU = $productoData['SKU'];
                $producto = Producto::where('estado_registro', 'A')->where('SKU', $SKU)->first();
                $cantidad = (float)$productoData['cantidad'];

                if ($producto) {
                    $precio_soles = isset($productoData['precio_soles']) ? (float)$productoData['precio_soles'] : null;
                    $precio_dolares = isset($productoData['precio_dolares']) ? (float)$productoData['precio_dolares'] : null;

                    if ($precio_soles !== null) {
                        $data['moneda'] = 'S/';
                        $data['productos'][] = [
                            'producto' => $producto->articulo->nombre,
                            'unidad' => $producto->unidad_medida->nombre,
                            'cantidad' => $cantidad,
                            'precio' => $precio_soles,

                            'total' => $precio_soles * $cantidad,
                        ];
                        $data['total_productos'] += $precio_soles * $cantidad;
                        $totalEnLetras = strtoupper($this->convertirNumeroEnLetras($data['total_productos'], 'Soles'));
                    } else if ($precio_dolares !== null) {
                        $data['moneda'] = '$/';
                        $data['productos'][] = [
                            'producto' => $producto->articulo->nombre,
                            'unidad' => $producto->unidad_medida->nombre,
                            'cantidad' => $cantidad,
                            'precio' => $precio_dolares,
                            'total' => $precio_dolares * $cantidad,
                        ];
                        $data['total_productos'] += $precio_dolares * $cantidad;
                        $totalEnLetras = strtoupper($this->convertirNumeroEnLetras($data['total_productos'], 'Dólares'));
                    }

                    OrdenProducto::updateOrCreate(
                        [
                            'orden_compra_id' => $orden_compra->id,
                            'producto_id' => $producto->id
                        ],
                        [
                            'cantidad' => $cantidad,
                            'precio_soles' => $precio_soles,
                            'precio_dolares' => $precio_dolares
                        ]
                    );
                }
            }

            // Calcular el subtotal
            $subTotal = (float) $data['total_productos'] / 1.18;
            $data['sub_total'] = number_format($subTotal, 2);

            // Calcular IGV (18%)
            $data['igv'] = number_format($subTotal * 0.18, 2);

            // Calcular el total
            $data['total'] = number_format($data['total_productos'], 2);

            // Actualizar los campos de la orden de compra
            $orden_compra->update([
                'sub_total' => $subTotal,
                'IGV' => $data['igv'],
                'total' => $data['total'],
                'estado_registro' => 'A', // Ajusta según tus necesidades
            ]);

            // Renderiza la vista con los datos
            $html = view('pdf.ejemplo', [
                'data' => $data,
                'totalEnLetras' => $totalEnLetras
            ])->render();

            // Genera el PDF
            $pdf = Pdf::loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $output = $pdf->output();

            // Guarda el PDF en almacenamiento público
            $pdf_nombre = $orden_compra_numero . '-orden_compra.pdf';
            Storage::disk('public')->put("orden_compra/pdf/" . $pdf_nombre, $output);

            // Commit de la transacción
            DB::commit();

            // Devuelve la URL del archivo generado para descargar
            $ruta_archivo = asset("storage/orden_compra/pdf/" . $pdf_nombre);

            // Actualiza la URL del PDF en la base de datos
            $orden_compra->update(['url_pdf' => $ruta_archivo]);

            return response()->json(["resp" => "Orden de Compra Editada correctamente", "url" => $ruta_archivo], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }



    public function get()
    {
        try {
            $orden_compra = OrdenCompra::with('proveedor', 'orden_producto.producto.articulo', 'orden_producto.producto.unidad_medida')->where('estado_registro', 'A')->get();
            if (!$orden_compra) {
                return response()->json(['resp' => 'Ordenes No Existentes'], 500);
            }
            return response()->json(['data' => $orden_compra], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function descargarPdf($orden_compra)
    {
        try {
            // Generar el nombre del archivo PDF basado en el número de orden de compra
            $pdf_nombre = $orden_compra . '-orden_compra.pdf';

            // Verificar si el archivo PDF existe
            $pdfPath = storage_path('app/public/orden_compra/pdf/' . $pdf_nombre);       // Ruta completa al archivo PDF

            if (!file_exists($pdfPath)) {
                return response()->json(['error' => 'El archivo PDF no existe.'], 404);
            }
            // Descargar el archivo PDF
            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            return response()->download($pdfPath, $pdf_nombre, $headers);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function mostrarPdf($orden_compra)
    {
        try {
            // Generar el nombre del archivo PDF basado en el número de orden de compra
            $pdf_nombre = $orden_compra . '-orden_compra.pdf';

            // Verificar si el archivo PDF existe
            $pdfPath = storage_path('app/public/orden_compra/pdf/' . $pdf_nombre); // Ruta completa al archivo PDF

            if (!file_exists($pdfPath)) {
                return response()->json(['error' => 'El archivo PDF no existe.'], 404);
            }

            // Preparar headers para el PDF
            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            // Descargar el archivo PDF
            return response()->file($pdfPath, $headers);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }

    public function createProductoOrden(Request $request)
    {
        try {
            DB::beginTransaction();
            // Validación de campos obligatorios
            if (empty($request->SKU)) {
                return response()->json(['resp' => 'SKU no ingresado, o no valido'], 500);
            }
            if (empty($request->nombre)) {
                return response()->json(['resp' => 'Nombre del Producto no Ingresado'], 500);
            }
            if (empty($request->unidad_medida_id)) {
                return response()->json(['resp' => 'Seleccione la Unidad de Medida'], 500);
            }
            if (empty($request->sub_familia_id)) {
                return response()->json(['resp' => 'Seleccione La Familia y Sub Familia'], 500);
            }
            $articulo = Articulo::create([
                'nombre' => $request->nombre,
                'sub_familia_id' => $request->sub_familia_id,
            ]);
            $sku_existente = Producto::where('estado_registro', 'A')->where('SKU', $request->SKU)->first();
            if ($sku_existente) {
                return response()->json(['resp' => 'El SKU ingresado ya existe, intentalo nuevamente'], 500);
            }
            $producto = Producto::create([
                'articulo_id' => $articulo->id,
                'SKU' => $request->SKU,
                'unidad_medida_id' => $request->unidad_medida_id
            ]);
            $inventario = Inventario::create([
                'producto_id' => $producto->id
            ]);
            $inventario_valorizado = InventarioValorizado::create([
                'inventario_id' => $inventario->id
            ]);
            // return response()->json($inventario_valorizado);
            DB::commit();
            return response()->json(['resp' => 'Producto creado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    public function GetUltimaOrdenCompra()
    {
        try {
            $orden_compra = OrdenCompra::where('estado_registro', 'A')->orderBy('id', 'desc')->first();
            if (!$orden_compra) {
                return response()->json(['resp' => 1150], 200);
            };
            $orden_compra_numero = $orden_compra->numero_orden;
            $orden_compra_numero++;
            if (!$orden_compra_numero) {
                return response()->json(['resp' => 'Numero de Orden de compra no existente'], 500);
            };
            return response()->json(['resp' => $orden_compra_numero], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Algo salió mal", "message" => $e->getMessage()], 500);
        }
    }
    private function convertirNumeroEnLetras($numero, $moneda = 'Soles')
    {
        // Usa NumberFormatter para la parte entera
        $formatter = new \NumberFormatter('es_PE', \NumberFormatter::SPELLOUT);

        // Divide el número en parte entera y decimal
        $partes = explode('.', number_format($numero, 2, '.', ''));
        $parteEntera = $partes[0];
        $parteDecimal = $partes[1];

        // Convierte la parte entera a letras
        $parteEnteraEnLetras = $formatter->format($parteEntera);

        // Formatea la salida final
        $monedaTexto = ($moneda === 'Dólares') ? (($parteEntera == 1) ? 'DÓLAR' : 'DÓLARES') : (($parteEntera == 1) ? 'SOL' : 'SOLES');
        $resultado = strtoupper("SON : $parteEnteraEnLetras CON $parteDecimal/100 $monedaTexto");

        return $resultado;
    }
}
