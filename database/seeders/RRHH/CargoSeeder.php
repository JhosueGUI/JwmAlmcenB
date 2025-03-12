<?php

namespace Database\Seeders\RRHH;

use App\Models\RRHH\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $administraciones = [
            "ASISTENTE ADMINISTRATIVO",
            "ASISTENTE CONTABLE",
            "ASISTENTE DE ADMINISTRACIÓN Y FINANZAS",
            "ASISTENTE DE RECURSOS HUMANOS",
            "AUXILIAR CONTABLE",
            "DESARROLLADOR DE PROGRAMACIÓN",
            "ENCARGADA DE FACTURACIÓN Y COBRANZAS",
            "JEFE DE CONTABILIDAD",
            "JEFE DE FINANZAS",
        ];
        foreach ($administraciones as $administracion) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $administracion,
                'area_id' => 1
            ]);
        }
        $operaciones = [
            'AXULIAR DE ESCOLTA',
            'CONDUCTOR DE SEMI TRAILER',
            'CONDUCTOR DE CAMION',
            'CONDUCTOR DE SEMI TRAILER',
            'COORDINADOR DE OPERACIONES',
            'ENCARGADO DE PROGRAMACIÓN Y FLOTA',
            'JEFE DE PROYECTOS',
            'OPERADOR DE CAMION GRUA',
            'RIGGER DE GRUA'
        ];
        foreach ($operaciones as $operacion) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $operacion,
                'area_id' => 2
            ]);
        }
        $comerciales = [
            'GERENTE COMERCIAL',
            'JEFE COMERCIAL',
            'ASISTENTE COMERCIAL'
        ];
        foreach ($comerciales as $comercial) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $comercial,
                'area_id' => 3
            ]);
        }
        $almacenes = [
            'ASISTENTE DE ALMACEN'
        ];
        foreach ($almacenes as $almacen) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $almacen,
                'area_id' => 6
            ]);
        }
        $limpiezas = [
            'ENCARGADA DE LIMPIEZA'
        ];
        foreach ($limpiezas as $limpieza) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $limpieza,
                'area_id' => 8
            ]);
        }
        $vigilancias = [
            'JEFE DE SEGURIDAD',
            'AGENTE DE SEGURIDAD',
        ];
        foreach ($vigilancias as $vigilancia) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $vigilancia,
                'area_id' => 4
            ]);
        }
        $gerencias = [
            'GERENTE GENERAL',
            'ASISTENTE DE GERENCIA COMERCIAL',
        ];
        foreach ($gerencias as $gerencia) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $gerencia,
                'area_id' => 9
            ]);
        }
        $LOGISTICAS = [
            'MANTENIMIENTO',
            'JEFE DE MANTEMIENTO',
            'ASISTENTE DE MANTENIMIENTO',
        ];
        foreach ($LOGISTICAS as $LOGISTICA) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $LOGISTICA,
                'area_id' => 5
            ]);
        }
        $flotas=[
            'RIGGER DE GRUA',
            'OPERADOR DE CAMION GRUA',
            'CONDUCTOR DE SEMI TRAILER',
            'CONDUCTOR DE CAMION',
            'COORDINADOR DE OPERACIONES',
            'AXULIAR DE ESCOLTA',
        ];
        foreach ($flotas as $flota) {
            Cargo::firstOrcreate([
                'nombre_cargo' => $flota,
                'area_id' => 7
            ]);
        }
    }
}
