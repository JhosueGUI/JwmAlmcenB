<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioValorizado extends Model
{
    protected $table='inventario_valorizado';
    protected $fillable=[
        'inventario_id',
        'valor_unitario_soles',
        'valor_unitario_dolares',
        'valor_inventario_soles',
        'valor_inventario_dolares',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertence a inventario(hijo-padre)
    public function inventario(){
        return $this->belongsTo(Inventario::class,'inventario_id','id');
    }
    
    // //pertenece a transaccion (hijo-padre)
    // public function transaccion(){
    //     return $this->belongsTo(Transaccion::class,'transaccion_id','id');
    // }
}
