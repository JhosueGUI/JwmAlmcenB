<?php

namespace Database\Seeders;

use App\Models\Acceso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccesoSeeder extends Seeder
{
    public function run(): void
    {
        $accesos = [
            [
                'nombre'=> 'Almacen',
                'subAcceso' => [
                    'Inventario'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                    'Ingreso'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                    'Salida'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                    'Orden de Compra'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                ]
            ],
            [
                'nombre' => 'Monitoreo',
                'subAcceso' => [
                    'Alertas'=>[
                        'Ver'
                    ],
                    'Seguimiento'=>[
                        'Ver'
                    ],
                ]
            ],
            [
                'nombre' => 'RRHH',
                'subAcceso' => [
                    'Asistencias'=>[
                        'Ver'
                    ],
                    'Horarios'=>[
                        'Ver'
                    ],
                ]
            ],
            [
                'nombre'=> 'Finanzas',
                'subAcceso' => [
                    'Movimientos'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                        'Trazabilidad',
                    ],
                ]
            ],
            [
                'nombre' => 'Mantenimiento',
                'subAcceso' => [
                    'Ingreso MMTTO'=>[
                        'Ver'
                    ],
                    'Formulario MMTTO'=>[
                        'Ver'
                    ],
                ]
            ],
            [
                'nombre' => 'Administración',
                'subAcceso' => [
                    'Proveedor'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                    'Personal'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                    'Flota'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ],
                    'Roles'=>[
                        'Crear',
                        'Editar',
                        'Eliminar',
                    ]
                ]
            ],
            [
                'nombre' => 'Reportes',
                'subAcceso' => [
                    'Rep Productos',
                    'EPPS',
                    'Implementos'
                ]
            ],
        ];
        foreach ($accesos as $acceso) {
            if (is_array($acceso)) {
                $acceso_padre = Acceso::firstOrCreate([
                    'nombre' => $acceso['nombre'],
                    'tipo_acceso' => 1,
                    'ruta' => $acceso['nombre']
                ]);
                foreach ($acceso['subAcceso'] as $clave => $valor) {
                    if (is_array($valor)) {
                        $sub_acceso_padre = Acceso::firstOrCreate([
                            'nombre' => $clave,
                            'tipo_acceso' => 2,
                            'ruta' => $clave,
                            'acceso_padre_id' => $acceso_padre->id
                        ]);
                        // Iteramos sobre los sub-sub-accesos
                        foreach ($valor as $subSubAcceso) {
                            Acceso::firstOrCreate([
                                'nombre' => $subSubAcceso,
                                'tipo_acceso' => 3,
                                'ruta' => $subSubAcceso,
                                'acceso_padre_id' => $sub_acceso_padre->id
                            ]);
                        }
                    } else {
                        Acceso::firstOrCreate([
                            'nombre' => $valor,
                            'tipo_acceso' => 2,
                            'ruta' => $valor,
                            'acceso_padre_id' => $acceso_padre->id
                        ]);
                    }
                }
            } else {
                Acceso::firstOrCreate([
                    'nombre' => $acceso,
                    'tipo_acceso' => 1,
                    'ruta' => $acceso
                ]);
            }
        }
    }
}