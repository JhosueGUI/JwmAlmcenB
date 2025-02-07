<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table='rol';
    protected $fillable=[
        'nombre',
        'tipo_acceso',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a acceso_rol(padre-hijo)
    public function acceso_rol(){
        return $this->hasMany(AccesoRol::class);
    }
    //le da su id a user_rol(padre-hijo){
    public function user_rol(){
        return $this->hasMany(UsuarioRol::class);
    }
    //
    public function users()
    {
        return $this->belongsToMany(User::class, 'usuario_rol', 'rol_id', 'user_id');
    }
}
