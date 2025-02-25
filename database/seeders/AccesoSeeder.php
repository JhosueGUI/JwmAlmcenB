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
                    'Inventario',
                    'Ingreso',
                    'Salida',
                    'Orden de Compra',
                ]
            ],
            [
                'nombre' => 'RRHH',
                'subAcceso' => [
                    'Asistencias',
                    'Horarios',
                ]
            ],
            [
                'nombre'=> 'Finanzas',
                'subAcceso' => [
                    'Movimientos',
                ]
            ],
            [
                'nombre' => 'Mantenimiento',
                'subAcceso' => [
                    'Ingreso MMTTO'
                ]
            ],
            [
                'nombre' => 'Administración',
                'subAcceso' => [
                    'Proveedor',
                    'Personal',
                    'Flota',
                    'Roles'
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
                $acceso_padre = Acceso::firstOrcreate([
                    'nombre' => $acceso['nombre'],
                    'tipo_acceso' => 1,
                    'ruta' => $acceso['nombre']
                ]);
                foreach ($acceso['subAcceso'] as $subAcceso) {
                    Acceso::firstOrCreate([
                        'nombre' => $subAcceso,
                        'tipo_acceso' => 2,
                        'ruta' => $subAcceso,
                        'acceso_padre_id' => $acceso_padre->id
                    ]);
                }
            }
            Acceso::firstOrcreate([
                'nombre' => $acceso,
                'tipo_acceso' => 1,
                'ruta' => $acceso
            ]);
        }
    }
}
