<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funciones extends Model
{
    protected $table='funciones';
    protected $fillable=[
        'personal_id',
        'inventario_valorizado',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a personal(hijo-padre)
    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }
    //pertenece a inventario valorizado (hijo-padre)
    public function inventario_valorizado(){
        return $this->belongsTo(InventarioValorizado::class,'inventario_valorizado_id','id');
    }
}
