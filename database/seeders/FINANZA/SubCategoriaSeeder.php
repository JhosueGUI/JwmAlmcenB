<?php

namespace Database\Seeders\FINANZA;

use App\Models\FINANZA\Categoria;
use App\Models\FINANZA\SubCategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $INGRESO_DE_SERVICIOS = [
            'Cobro',
            'Factura negociada',
            'Alquiler de cochera',
            'Alquiler de montacarga',
            'Cobro sin factura',
        ];
        foreach ($INGRESO_DE_SERVICIOS as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 1
            ]);
        }
        $INGRESO_DE_FINANCIAMIENTO=[
            'Prestamo de bancos',
            'Prestamo de otros',
            'Capital de trabajo',
        ];
        foreach ($INGRESO_DE_FINANCIAMIENTO as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 2
            ]);
        }
        $INGRESO_NO_OPERACIONALES=[
            'Venta',
            'Devolución',
            'Devolución de Prestamo',
            'Vuelto',
            'Devolución de proveedores',
            'Ingreso a cta Propia',
            'Banco BBVA - Identificar si es un cobro de una factura JWM',
            'Banco Scotiabank - Identificar si es un cobro de una factura JWM',
            'Banco Interbank - Identificar si es un cobro de una factura JWM',
            'Banco Bcp dólar - Identificar si es un cobro de una factura JWM'
        ];
        foreach ($INGRESO_NO_OPERACIONALES as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 3
            ]);
        }
        $COSTOS_DE_SERVICIO_VARIABLE=[
            'Compra combustible cisterna',
            'Compra combustible en ruta',
            'Gastos de viaje (Peajes,Viatico y otros)',
            'Tercero transporte proveedor',
            'Otros Costos variables (Estibas,resguardo)',
            'Neumaticos',
            'Mtto Correctivo',
            'Mtto Preventivo'
        ];
        foreach ($COSTOS_DE_SERVICIO_VARIABLE as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 4
            ]);
        }
        $GASTO_DE_SERVICIO_VARIABLE=[
            'Comisión ventas',
            'Gastos comerciales'
        ];
        foreach ($GASTO_DE_SERVICIO_VARIABLE as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 5
            ]);
        }
        $COSTOS_FIJOS=[
           'Planilla conductores y operadores',
           'Planilla operaciones',
           'Vacaciones y Horas extras',
           'CTS Y Gratificacion',
           'Póliza Vehicular',
           'Póliza Carga',
           'Póliza Trek',
           'SCTR y Vida Ley',
           'GPS',
           'Linea celular conductores',
           'SOAT',
           'Revision tecnica',
           'Impuesto Vehicular'
        ];
        foreach ($COSTOS_FIJOS as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 6
            ]);
        }
        $GASTOS_FIJOS=[
            'Planilla Administrativo',
            'Planilla comercial + Logistic + Mtto',
            'Planilla Seguridad + Limpieza',
            'Poliza EPS',
            'Linea Celular Administrativos',
            'Luz del sur + Internet + Sedapal',
            'Alquiler Almacen + Taller antiguo',
            'Alquiler Oficina + Los Molles',
            'Equifax + Sistema Facturacion'
        ];
        foreach ($GASTOS_FIJOS as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 7
            ]);
        }
        $GASTOS_OPERACIONALES=[
            'EMOS',
            'Capacitaciones',
            'Antecedentes policiales/penales'
        ];
        foreach ($GASTOS_OPERACIONALES as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 8
            ]);
        }
        $GASTOS_DE_MTTO_Y_REPARACION=[
            'Accesorio',
            'Lubricante',
            'Filtro',
            'Mantenimiento',
            'Pintura',
            'Neumatico',
            'Urea',
            'Herramienta',
            'Repuesto',
            'Implemento',
            'Deducible - Seguro',
            'Stock almacén',
            'Uniforme - Epps'
        ];
        foreach ($GASTOS_DE_MTTO_Y_REPARACION as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 9
            ]);
        }
        $GASTOS_ADMINISTRATIVOS=[
            'AFP',
            'Abogado',
            'Licencia de Funcionamiento',
            'Viáticos de viaje',
            'Tramites',
            'Motorizado',
            'Utiles limpieza',
            'Utiles oficina',
            'Art. de ferretería'
        ];
        foreach ($GASTOS_ADMINISTRATIVOS as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' =>strtoupper($subcategoria),
                'categoria_id' => 10
            ]);
        }
        $GASTOS_FINANCIEROS_Prestamos=[
            'Reactiva William (39K) - Agost.24',
            'Prestamo JWM (Roy) - Agost.24',
            'Reactiva Hilda (8K) - Octubr.24',
            'MiBanco William (150K) - Oct.24',
            'BCP LBA (150K) - Oct.24',
            'Scotiabank Franklin (42K) - Dic.24',
            'MiBanco Eleuterio (150K) - Dic.24',
            'MiBanco Jeans (132K) - May.25',
            'MiBanco Eleuterio (100K) - Jul.25',
            'Reactiva JWM (2.5M) - Agost.24',
            'BCP FSJ Op.(250K) - Agost.25',
            'Reactiva FSJ (211K) - Setiembr 25',
            'MiBanco Lissety (150K) - Oct.25',
            'MiBanco Jeans (70K) - Marz.26',
            'BCP JR Prodesa (100K) - Abri.26',
            'Leasing Scotiabank (4 Inter) - May.26',
            'Leasing Banbif (2 Sitrack) - Julio.26',
            'Leasing Scotiabank (4X4) - Agost.26',
            'Leasing Banbif (Foton) - Nov.27',
            'Impuesto ITF',
            'Mantenimiento BCP',
            'Comisión detracción',
            'Estado cuenta',
            'Mant Adic Neg',
            'Mantenimiento TLC',
            'Com. Otra. Local',
            'Letras descuentos',
            'Amortización terreno 4000 Mt2 ($580K)',
            'Amortización terreno 663 Mt2 ($104K)'
        ];
        foreach ($GASTOS_FINANCIEROS_Prestamos as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 11
            ]);
        }
        $INVERSIONES=[
            'Compra de activo - Detallar'
        ];
        foreach ($INVERSIONES as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' =>strtoupper($subcategoria),
                'categoria_id' => 12
            ]);
        }
        $GASTOS_NO_OPERACIONALES=[
            'Retiro William',
            'Hacienda Pampaya',
            'Medicina',
            'Otros Gastos'
        ];
        foreach ($GASTOS_NO_OPERACIONALES as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 13
            ]);
        }
        $SALIDAS_NO_RELACIONADAS_Y_BANCARIZACIONES=[
            'Compra dólar',
            'Bancarizar factura Camionero',
            'Yesenia',
            'Estefani',
            'William',
            'Ingrid',
            'Deposito por error',
            'Otros'
        ];
        foreach ($SALIDAS_NO_RELACIONADAS_Y_BANCARIZACIONES as $subcategoria) {
            SubCategoria::create([
                'nombre_sub_categoria' => strtoupper($subcategoria),
                'categoria_id' => 14
            ]);
        }
    }
}
