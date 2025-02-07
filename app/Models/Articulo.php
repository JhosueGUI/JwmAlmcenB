<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table='articulo';
    protected $fillable=[
        'nombre',
        'precio_soles',
        'precio_dolares',
        'sub_familia_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a sub familia (hijo-padre)
    public function sub_familia(){
        return $this->belongsTo(SubFamilia::class,'sub_familia_id','id');
    }
    //le da su id a producto (padre-hijo)
    public function producto (){
        return $this->hasMany(Producto::class);
    }
}
