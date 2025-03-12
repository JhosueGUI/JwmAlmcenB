<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table='producto';
    protected $fillable=[
        'SKU',
        // 'marca',
        'articulo_id',
        'unidad_medida_id',
        // 'ubicacion_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a articulo (hijo-padre)
    public function articulo(){
        return $this->belongsTo(Articulo::class,'articulo_id','id');
    }
    //pertenece a unidad medida (hijo-padre)
    public function unidad_medida(){
        return $this->belongsTo(UnidadMedida::class,'unidad_medida_id','id');
    }

    //le da su id a transaccion(padre-hijo)
    public function transaccion(){
        return $this->hasMany(Transaccion::class);
    }
    //le da su id a proveedor producto(padre-hijo)
    public function proveedor_producto(){
        return $this->hasMany(ProveedorProducto::class);
    }
    //le da su id a inventario(padre-hijo)
    public function inventario(){
        return $this->hasOne(Inventario::class);
    }
    //le da su id a orden_producto(padre-hijo)
    public function orden_producto(){
        return $this->hasMany(OrdenProducto::class);
    }

}
