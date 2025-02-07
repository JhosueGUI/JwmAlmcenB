<?php

namespace Database\Seeders;

use App\Models\Articulo;
use App\Models\Inventario;
use App\Models\InventarioValorizado;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarioValorizadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articulo=Articulo::firstOrcreate([
            'nombre'=>'SOGA 3/8 PARA GRUA',
            'sub_familia_id'=>50
        ]);
        $producto=Producto::firstOrcreate([
            'SKU'=>601,
            'articulo_id'=>$articulo->id,
            'unidad_medida_id'=>8,
        ]);
        $inventario=Inventario::firstOrcreate([
            'producto_id'=>$producto->id,
            'estado_operativo_id'=>3,
            'ubicacion_id'=>1
        ]);
        InventarioValorizado::firstOrcreate([
            'inventario_id'=>$inventario->id
        ]);

        $articulo=Articulo::firstOrcreate([
            'nombre'=>'FILTRO DE ACEITE ME228898',
            'sub_familia_id'=>99
        ]);
        $producto=Producto::firstOrcreate([
            'SKU'=>533,
            'articulo_id'=>$articulo->id,
            'unidad_medida_id'=>8,
        ]);
        $inventario=Inventario::firstOrcreate([
            'producto_id'=>$producto->id,
            'estado_operativo_id'=>2,
            'ubicacion_id'=>1
        ]);
        InventarioValorizado::firstOrcreate([
            'inventario_id'=>$inventario->id
        ]);
    }
}
