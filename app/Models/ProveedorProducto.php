<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProveedorProducto extends Model
{
    protected $table='proveedor_producto';
    protected $fillable=[
        'proveedor_id',
        'producto_id',
        'identificador',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a proveedor(hijo-padre)
    public function proveedor(){
        return $this->belongsTo(Proveedor::class,'proveedor_id','id');
    }
    //pertenece a producto(hijo-padre)
    public function producto(){
        return $this->belongsTo(Producto::class,'producto_id','id');
    }
}
