<?php

namespace Database\Seeders;

use App\Models\SubFamilia;
use Illuminate\Database\Seeder;

class SubFamiliaSeeder extends Seeder
{
    public function run(): void
    {
        //BEBIDAS
        $Bebidas = [
            'AGUAS',
        ];

        foreach ($Bebidas as $Bebida) {
            SubFamilia::firstOrCreate([
                'nombre' => $Bebida,
                'familia_id' => 1
            ]);
        }


        //COMUNICACION
        $Comunicaciones = [
            'RADIOS',
        ];

        foreach ($Comunicaciones as $Comunicacion) {
            SubFamilia::firstOrCreate([
                'nombre' => $Comunicacion,
                'familia_id' => 2
            ]);
        }


        //HERRAMIENTA
        $Herramientas = [
            'ACCESORIOS DE HERRAMIENTAS',
            'EQUIPO DE CARGA',
            'ESLINGAS',
            'FAJAS',
            'GANCHOS',
            'GRILLETES',
            'RATCHS',
        ];

        foreach ($Herramientas as $Herramienta) {
            SubFamilia::firstOrCreate([
                'nombre' => $Herramienta,
                'familia_id' => 3
            ]);
        }


        //IMPLEMENTO
        $Implementos = [
            'CABLES',
            'CADENAS',
            'CANDADOS',
            'CINTAS AISLANTES',
            'CINTAS REFLECTIVAS',
            'CIRCULINAS',
            'FAROS',
            'INSUMOS DE IMPLEMENTO',
            'PRECINTOS',
            'PULPOS',
            'TEMPLADORES'
        ];

        foreach ($Implementos as $Implemento) {
            SubFamilia::firstOrCreate([
                'nombre' => $Implemento,
                'familia_id' => 4
            ]);
        }


        //JARDINERIA
        $Jardinerias = [
            'INSUMOS DE JARDINERIA',
        ];

        foreach ($Jardinerias as $Jardineria) {
            SubFamilia::firstOrCreate([
                'nombre' => $Jardineria,
                'familia_id' => 5
            ]);
        }


        //LIMPIEZA
        $Limpiezas = [
            'INSUMOS DE LIMPIEZA',
            'BOLSAS'
        ];
        foreach ($Limpiezas as $Limpieza) {
            SubFamilia::firstOrCreate([
                'nombre' => $Limpieza,
                'familia_id' => 6
            ]);
        }
        //MANTENIMIENTO
        $Mantenimientos = [
            'INSUMOS DE MANTENIMIENTO',
        ];

        foreach ($Mantenimientos as $Mantenimiento) {
            SubFamilia::firstOrCreate([
                'nombre' => $Mantenimiento,
                'familia_id' => 7
            ]);
        }
        //MTTO CORRECTIVO
        $MttoCorrectivos = [
            'DISCOS DE CORTE',
            'DISCOS DE DESBASTE',
            'GRASAS',
            'INSUMOS CORRECTIVOS',
            'SOLDADURA'
        ];
        foreach ($MttoCorrectivos as $MttoCorrectivo) {
            SubFamilia::firstOrCreate([
                'nombre' => $MttoCorrectivo,
                'familia_id' => 8
            ]);
        }
        //MTTO PREVENTIVO
        $MttoPreventivos = [
            'ACEITES LUBRICANTES',
            'APLICADORES DE SILICONA',
            'FILTROS',
            'GRASAS EN SPRAY',
            'GRASAS EN BARRA',
            'ILUMINACIÓN',
            'INSUMOS DE REPARACIÓN',
            'INSUMOS PARA BATERÍAS',
            'INSUMOS PREVENTIVOS',
            'MANGUERAS',
            'MANGUERAS DE AIRE',
            'PRODUCTOS AFLOJATODO',
            'PRODUCTOS DE LIMPIEZA',
            'SILICONAS INDUSTRIALES',
            'SILICONAS PARA TABLÓN',
            'TIMBRES INDUSTRIALES'

        ];
        foreach ($MttoPreventivos as $MttoPreventivo) {
            SubFamilia::firstOrCreate([
                'nombre' => $MttoPreventivo,
                'familia_id' => 9
            ]);
        }
        //NEUMATICOS
        $Neumaticos = [
            'LLANTAS',
            'AROS',
        ];
        foreach ($Neumaticos as $Neumatico) {
            SubFamilia::firstOrCreate([
                'nombre' => $Neumatico,
                'familia_id' => 10
            ]);
        }
        //SEGURIDAD
        $Seguridades = [
            'ACCESORIOS',
            'ACCESORIOS DE SEGURIDAD',
            'BOTIQUINES',
            'CALZADO DE SEGURIDAD',
            'GUANTES',
            'INSUMOS DE SEGURIDAD',
            'KIT DE SEGURIDAD',
            'CALZADO DE SEGURIDAD',
            'ROPA DE TRABAJO',
            'ROPA DE SEGURIDAD',
            'TAMPONES'
        ];
        foreach ($Seguridades as $Seguridade) {
            SubFamilia::firstOrCreate([
                'nombre' => $Seguridade,
                'familia_id' => 11
            ]);
        }
        //ÚTILES DE OFICINA
        $UtilesDeOficinas = [
            'ARCHIVADORES',
            'CINTAS',
            'CINTAS DE EMBALAJE',
            'CUADERNOS',
            'HOJAS',
            'INSUMOS',
            'LÁPICES',
            'PLUMONES',
            'SOBRES',


        ];
        foreach ($UtilesDeOficinas as $UtilesDeOficina) {
            SubFamilia::firstOrCreate([
                'nombre' => $UtilesDeOficina,
                'familia_id' => 12
            ]);
        }
        //Combustible
        $combustibles = [
            'PETROLEO',
            'DIÉSEL',
            'GASOLINA'
        ];
        
        foreach ($combustibles as $cumbustible) {
            SubFamilia::firstOrCreate([
                'nombre' => $cumbustible,
                'familia_id' => 13
            ]);
        }
    }
}
