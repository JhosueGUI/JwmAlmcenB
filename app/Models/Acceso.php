<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{
    protected $table='acceso';
    protected $fillable=[
        'nombre',
        'tipo_acceso',
        'ruta',
        'acceso_padre_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a Acceso Rol (Padre Hijo)
    public function acceso_rol(){
        return $this->hasMany(AccesoRol::class);
    }
    public function sub_acceso()
    {
        return $this->hasMany(Acceso::class, 'acceso_padre_id');
    }
}
