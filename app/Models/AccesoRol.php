<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoRol extends Model
{
    protected $table='acceso_rol';
    protected $fillable=[
        'rol_id',
        'acceso_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a Acceso (hijo-padre)
    public function acceso(){
        return $this->belongsTo(Acceso::class,'acceso_id','id');
    }
    //pertenece a Rol (hijo-padre)
    public function rol(){
        return $this->belongsTo(Rol::class,'rol_id','id');
    }
}
