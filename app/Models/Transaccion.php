<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table='transaccion';
    protected $fillable=[
        'producto_id',
        'tipo_operacion',
        'precio_unitario_soles',
        'precio_total_soles',
        'precio_unitario_dolares',
        'precio_total_dolares',
        'observaciones',
        'marca',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a producto (hijo-padre)
    public function producto(){
        return $this->belongsTo(Producto::class,'producto_id','id');
    }
    //le da su id a ingreso(padre-hijo)
    public function ingreso(){
        return $this->hasMany(Ingreso::class);
    }
    //le da su id a salida (padre-hijo)
    public function salida(){
        return $this->hasMany(Salida::class);
    }
    //le pa su id a combustible(padre-hijo)
    public function combustible(){
        return $this->hasMany(Combustible::class);
    }
}
