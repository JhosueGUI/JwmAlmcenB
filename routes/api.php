<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\Combustible\GrifoController;
use App\Http\Controllers\FlotaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\InventarioValorizadoController;
use App\Http\Controllers\OrdenDeCompraController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\Reportes\ImplementosController;
use App\Http\Controllers\Reportes\ReporteEppController;
use App\Http\Controllers\Reportes\ReporteImplementoController;
use App\Http\Controllers\Reportes\ReporteProductoController;
use App\Http\Controllers\RequerimientoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SalidaCombustibleController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\SubFamiliaController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\DestinoCombustibleController;
use App\Http\Controllers\Finanza\ClienteController;
use App\Http\Controllers\Finanza\EmpresaController;
use App\Http\Controllers\Finanza\EstadoComprobanteController;
use App\Http\Controllers\Finanza\ModoController;
use App\Http\Controllers\Finanza\MonedaController;
use App\Http\Controllers\Finanza\MovimientoController;
use App\Http\Controllers\Finanza\PersonaController;
use App\Http\Controllers\Finanza\ProveedorController as FinanzaProveedorController;
use App\Http\Controllers\Finanza\RendicionController;
use App\Http\Controllers\Finanza\SubCategoriaController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\ServicioExterno\ApiTerceroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Cors;

Route::group(['prefix' => 'orden_compra'], function () {
    Route::post('/generar', [OrdenDeCompraController::class, 'generarOrden']);
    Route::get('/descargar/{orden_compra}', [OrdenDeCompraController::class, 'descargarPdf']);
    Route::get('/observar/{orden_compra}', [OrdenDeCompraController::class, 'mostrarPdf']);
    Route::get('/exportar', [PersonalController::class, 'exportarPersonal']);
    Route::get('/exportar_proveedor', [ProveedorController::class, 'exportarProveedor']);
    Route::get('/exportar_flota', [FlotaController::class, 'exportarFlota']);
    Route::get('/exportar_ingreso', [IngresoController::class, 'exportarIngreso']);
    Route::get('/exportar_salida', [SalidaController::class, 'exportarSalida']);
    Route::get('/exportar_inventario', [InventarioValorizadoController::class, 'exportarInventario']);
});
Route::group(['prefix' => 'reporte'], function () {
    Route::get('/consumo/placa', [SalidaCombustibleController::class, 'ExportarConsumoPorPlaca']);
});
Route::group(['middleware' => [Cors::class]], function () {
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::group(['middleware' => ['jwt.auth', Cors::class]], function () {
        Route::group(['prefix' => 'almacen'], function () {
            Route::group(['prefix' => 'articulo'], function () {
                Route::get('/get', [ArticuloController::class, 'get']);
                Route::get('/show/{articuloID}', [ArticuloController::class, 'show']);
                Route::post('/create', [ArticuloController::class, 'create']);
                Route::post('/update/{articuloID}', [ArticuloController::class, 'update']);
                Route::delete('/delete/{articuloID}', [ArticuloController::class, 'delete']);
            });
            Route::group(['prefix' => 'producto'], function () {
                Route::get('/get', [ProductoController::class, 'get']);
                Route::get('/show/{productoID}', [ProductoController::class, 'show']);
                Route::post('/create', [ProductoController::class, 'create']);
                Route::post('/update/{productoID}', [ProductoController::class, 'update']);
                Route::delete('/delete/{productoID}', [ProductoController::class, 'delete']);
                Route::get('/stock/{productoID}', [ProductoController::class, 'getEstockLogico']);
            });
            Route::group(['prefix' => 'inventario_valorizado'], function () {
                Route::get('/get', [InventarioValorizadoController::class, 'get']);
                Route::get('/show/{inventario_valorizadoID}', [InventarioValorizadoController::class, 'show']);
                Route::post('/create', [InventarioValorizadoController::class, 'create']);
                Route::post('/update/{inventario_valorizadoID}', [InventarioValorizadoController::class, 'update']);
                Route::delete('/delete/{inventario_valorizadoID}', [InventarioValorizadoController::class, 'delete']);
                Route::post('/importar', [InventarioValorizadoController::class, 'importarInventario']);
                Route::get('/ultimo_sku', [InventarioValorizadoController::class, 'ObtenerUltimoSku']);
            });
            Route::group(['prefix' => 'rol'], function () {
                Route::get('/get', [RolController::class, 'get']);
                Route::get('/show/{inventario_valorizadoID}', [RolController::class, 'show']);
                Route::post('/create', [RolController::class, 'create']);
                Route::post('/update/{inventario_valorizadoID}', [RolController::class, 'update']);
                Route::delete('/delete/{inventario_valorizadoID}', [RolController::class, 'delete']);
                Route::post('/asignar_acceso/{inventario_valorizadoID}', [RolController::class, 'asignarAcceso']);
            });
            Route::group(['prefix' => 'personal'], function () {
                Route::get('/get', [PersonalController::class, 'get']);
                Route::get('/get/transaccion', [PersonalController::class, 'getPersonalTransaccion']);
                Route::get('/get_flota', [PersonalController::class, 'getPersonalFlota']);
                Route::get('/show/{personalID}', [PersonalController::class, 'show']);
                Route::post('/create', [PersonalController::class, 'create']);
                Route::post('/update/{personalID}', [PersonalController::class, 'update']);
                Route::delete('/delete/{personalID}', [PersonalController::class, 'delete']);
                Route::post('/asignar_rol/{personalID}', [PersonalController::class, 'AsignarRol']);
                Route::post('/credenciales/{idPersonal}', [PersonalController::class, 'EnviarCredenciales']);
                Route::post('/archivo', [PersonalController::class, 'SubirArchivo']);
            });
            Route::group(['prefix' => 'proveedor'], function () {
                Route::get('/get', [ProveedorController::class, 'get']);
                Route::get('/show/{idProveedor}', [ProveedorController::class, 'show']);
                Route::post('/create', [ProveedorController::class, 'create']);
                Route::post('/update/{idProveedor}', [ProveedorController::class, 'update']);
                Route::delete('/delete/{idProveedor}', [ProveedorController::class, 'delete']);
                Route::get('/peticion/get', [ProveedorController::class, 'ConsultasApiGet']);
                Route::post('/importar', [ProveedorController::class, 'SubirProveedor']);
            });
            Route::group(['prefix' => 'ingreso'], function () {
                Route::get('/get', [IngresoController::class, 'get']);
                Route::get('/show/{idIngreso}', [IngresoController::class, 'show']);
                Route::get('/conversion', [IngresoController::class, 'probar']);

                Route::post('/create', [IngresoController::class, 'create']);
                Route::post('/update/{idIngreso}', [IngresoController::class, 'update']);
                Route::delete('/delete/{idIngreso}', [IngresoController::class, 'delete']);
                Route::post('/importar', [IngresoController::class, 'importarIngreso']);
            });
            Route::group(['prefix' => 'salida'], function () {
                Route::get('/get', [SalidaController::class, 'get']);
                Route::get('/show/{idSalida}', [SalidaController::class, 'show']);
                Route::post('/create', [SalidaController::class, 'create']);
                Route::post('/update/{idSalida}', [SalidaController::class, 'update']);
                Route::delete('/delete/{idSalida}', [SalidaController::class, 'delete']);
                Route::post('/importar', [SalidaController::class, 'importarSalida']);
            });
            Route::group(['prefix' => 'salida_combustible'], function () {
                Route::post('/create', [SalidaCombustibleController::class, 'crearSalidaCombustible']);
                Route::get('/get', [SalidaCombustibleController::class, 'listarSalidaCombustible']);
                Route::get('/get/combustible', [SalidaCombustibleController::class, 'GetStockCombustible']);
                Route::post('/importar', [SalidaCombustibleController::class, 'subirSalidaCombustible']);
                Route::delete('/delete/{idSalida}', [SalidaCombustibleController::class, 'elimarSalidaCombustible']);
                Route::post('/update/{idSalida}', [SalidaCombustibleController::class, 'EditarSalidaCombustible']);
            });
            Route::group(['prefix' => 'grifo'], function () {
                Route::get('/get', [GrifoController::class, 'get']);
                Route::post('/create', [GrifoController::class, 'create']);
            });
            Route::group(['prefix' => 'destino_combustible'], function () {
                Route::get('/get', [DestinoCombustibleController::class, 'getDestinoCombustible']);
            });
            Route::group(['prefix' => 'planilla'], function () {
                Route::get('/get', [PlanillaController::class, 'get']);
            });
            Route::group(['prefix' => 'cargo'], function () {
                Route::get('/get', [CargoController::class, 'get']);
            });
            Route::group(['prefix' => 'flota'], function () {
                Route::get('/get', [FlotaController::class, 'get']);
                Route::get('/get_unidad', [FlotaController::class, 'getUnidad']);
                Route::get('/show/{idFlota}', [FlotaController::class, 'show']);
                Route::post('/create', [FlotaController::class, 'create']);
                Route::post('/update/{idFlota}', [FlotaController::class, 'update']);
                Route::delete('/delete/{idFlota}', [FlotaController::class, 'delete']);
                Route::post('/importar', [FlotaController::class, 'importarFlota']);
            });
            Route::group(['prefix' => 'tipo_documento'], function () {
                Route::get('/get', [TipoDocumentoController::class, 'get']);
            });
            Route::group(['prefix' => 'sub_familia'], function () {
                Route::get('/get', [SubFamiliaController::class, 'get']);
                Route::get('/sub_familia', [SubFamiliaController::class, 'getSubFamilia']);
            });
            Route::group(['prefix' => 'unidad_medida'], function () {
                Route::get('/get', [RequerimientoController::class, 'getUnidad']);
            });
            Route::group(['prefix' => 'ubicacion'], function () {
                Route::get('/get', [RequerimientoController::class, 'getUbicacion']);
            });
            Route::group(['prefix' => 'estado_operativo'], function () {
                Route::get('/get', [RequerimientoController::class, 'getEstadoOperativo']);
            });
            Route::group(['prefix' => 'orden_compra'], function () {
                Route::get('/get', [OrdenDeCompraController::class, 'get']);
                Route::post('/producto/create', [OrdenDeCompraController::class, 'createProductoOrden']);
                Route::post('/update/{ordenId}', [OrdenDeCompraController::class, 'EditarOrdenCompra']);
                Route::get('/get/ultimo', [OrdenDeCompraController::class, 'GetUltimaOrdenCompra']);
            });
            Route::group(['prefix' => 'acceso'], function () {
                Route::get('/get', [RequerimientoController::class, 'getAccesos']);
            });
            Route::group(['prefix' => 'password'], function () {
                Route::post('/cambiar', [AuthController::class, 'cambiarPassword']);
            });
            Route::group(['prefix' => 'area'], function () {
                Route::get('/get', [AreaController::class, 'get']);
            });
            Route::group(['prefix' => 'reporte'], function () {
                Route::group(['prefix' => 'familia'], function () {
                    Route::get('/filtro', [ReporteProductoController::class, 'reporteFiltro']);
                    Route::get('/filtro/meses', [ReporteProductoController::class, 'reporteFiltroMes']);
                });
                Route::group(['prefix' => 'epps'], function () {
                    Route::get('/filtro', [ReporteEppController::class, 'reporteFiltro']);
                    Route::get('/filtro/mes', [ReporteEppController::class, 'reporteFiltroMes']);
                });
                Route::group(['prefix' => 'implementos'], function () {
                    Route::get('/filtro', [ReporteImplementoController::class, 'reporteFiltro']);
                    Route::get('/filtro/mes', [ReporteImplementoController::class, 'reporteFiltroMes']);
                });
            });
        });
        Route::group(['prefix' => 'finanza'], function () {
            Route::group(['prefix' => 'movimiento'], function () {
                Route::get('/get', [MovimientoController::class, 'get']);
                Route::post('/create', [MovimientoController::class, 'create']);
                Route::post('/update/{idMovimiento}', [MovimientoController::class, 'editarMovimiento']);
                Route::post('/trazabilidad/{idMovimiento}', [MovimientoController::class, 'crearTrazabilidad']);
            });
            Route::group(['prefix' => 'estado-comprobante'], function () {
                Route::get('/get', [EstadoComprobanteController::class, 'GetEstado']);
            });
            Route::group(['prefix' => 'rendicion'], function () {
                Route::get('/get', [RendicionController::class, 'getRendicion']);
            });
            Route::group(['prefix' => 'empresa'], function () {
                Route::get('/get', [EmpresaController::class, 'getEmpresa']);
            });
            Route::group(['prefix' => 'modo'], function () {
                Route::get('/get', [ModoController::class, 'getModo']);
            });
            Route::group(['prefix' => 'moneda'], function () {
                Route::get('/get', [MonedaController::class, 'getMoneda']);
            });
            Route::group(['prefix' => 'cliente'], function () {
                Route::get('/get', [ClienteController::class, 'getCliente']);
                Route::post('/create', [ClienteController::class, 'create']);
            });
            Route::group(['prefix' => 'sub_categoria'], function () {
                Route::get('/get', [SubCategoriaController::class, 'getSubCategoria']);
            });
            Route::group(['prefix' => 'persona'], function () {
                Route::post('/create', [PersonaController::class, 'create']);
                Route::get('/get', [PersonaController::class, 'get']);
            });
            Route::group(['prefix' => 'proveedor'], function () {
                Route::post('/create', [FinanzaProveedorController::class, 'create']);
                Route::get('/get', [FinanzaProveedorController::class, 'get']);
            });
        });
        Route::group(['prefix' => 'servicio'], function () {
            Route::group(['prefix' => 'persona_natural'], function () {
                Route::get('/get/{dni}', [ApiTerceroController::class, 'ObtenerPersonalApi']);
            });
            Route::group(['prefix' => 'persona_juridica'], function () {
                Route::get('/get/{ruc}', [ApiTerceroController::class, 'ObtenerProveedorApi']);
            });
        });
    });
});
