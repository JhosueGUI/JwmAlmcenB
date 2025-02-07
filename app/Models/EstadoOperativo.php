<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoOperativo extends Model
{
    protected $table='estado_operativo';
    protected $fillable=[
        'nombre',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a Inventario(padre-hijo)
    public function inventario(){
        return $this->hasMany(Inventario::class);
    }
}
