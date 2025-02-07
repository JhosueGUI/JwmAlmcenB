<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table='ubicacion';
    protected $fillable=[
        'codigo_ubicacion',
        'seccion',
        'estante',
        'piso',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    // //le da su id a producto (padre-hijo)
    // public function producto(){
    //     return $this->hasMany(Producto::class);
    // }

    //le da su id a inventario(padre-hijo)
    public function inventario(){
        return $this->hasMany(Inventario::class);
    }
}
