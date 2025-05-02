<?php

namespace Database\Seeders;
use App\Models\Acceso;
use Illuminate\Database\Seeder;

class AccesoSeeder extends Seeder
{
    public function run(): void
    {
        $accesos = [
            [
                'nombre'=> 'Almacen',
                'subAcceso' => [
                    'Inventario'=>['Crear', 'Editar', 'Eliminar'],
                    'Ingreso'=>['Crear', 'Editar', 'Eliminar'],
                    'Salida'=>['Crear', 'Editar', 'Eliminar'],
                    'Orden de Compra'=>['Crear', 'Editar', 'Eliminar'],
                ]
            ],
            [
                'nombre' => 'Monitoreo',
                'subAcceso' => [
                    'Alertas'=>['Ver'],
                    'Seguimiento'=>['Ver'],
                ]
            ],
            [
                'nombre' => 'RRHH',
                'subAcceso' => [
                    'Personal'=>['Crear', 'Editar', 'Eliminar'],
                    'Asistencias'=>['Ver'],
                    'Horarios'=>['Ver'],
                ]
            ],
            [
                'nombre'=> 'Finanzas',
                'subAcceso' => [
                    'Movimientos'=>['Crear', 'Editar', 'Eliminar', 'Trazabilidad'],
                ]
            ],
            [
                'nombre' => 'Mantenimiento',
                'subAcceso' => [
                    'Ingreso MMTTO'=>['Ver'],
                    'Formulario MMTTO'=>['Ver'],
                ]
            ],
            [
                'nombre' => 'Administración',
                'subAcceso' => [
                    'Proveedor'=>['Crear', 'Editar', 'Eliminar'],
                    'Flota'=>['Crear', 'Editar', 'Eliminar'],
                    'Roles'=>['Crear', 'Editar', 'Eliminar']
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

        $validIds = [];

        foreach ($accesos as $acceso) {
            $acceso_padre = Acceso::updateOrCreate(
                ['nombre' => $acceso['nombre'], 'tipo_acceso' => 1],
                ['ruta' => $acceso['nombre']]
            );
            $validIds[] = $acceso_padre->id;

            foreach ($acceso['subAcceso'] as $clave => $valor) {
                if (is_array($valor)) {
                    $sub_acceso_padre = Acceso::updateOrCreate(
                        ['nombre' => $clave, 'tipo_acceso' => 2, 'acceso_padre_id' => $acceso_padre->id],
                        ['ruta' => $clave]
                    );
                    $validIds[] = $sub_acceso_padre->id;

                    foreach ($valor as $subSubAcceso) {
                        $sub_acceso_hijo = Acceso::updateOrCreate(
                            ['nombre' => $subSubAcceso, 'tipo_acceso' => 3, 'acceso_padre_id' => $sub_acceso_padre->id],
                            ['ruta' => $subSubAcceso]
                        );
                        $validIds[] = $sub_acceso_hijo->id;
                    }
                } else {
                    $sub_acceso = Acceso::updateOrCreate(
                        ['nombre' => $valor, 'tipo_acceso' => 2, 'acceso_padre_id' => $acceso_padre->id],
                        ['ruta' => $valor]
                    );
                    $validIds[] = $sub_acceso->id;
                }
            }
        }

        // 🔥 Eliminamos los accesos que no están en la lista actual
        Acceso::whereNotIn('id', $validIds)->delete();
    }
}
