<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table='user_rol';
    protected $fillable=[
        'rol_id',
        'user_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a rol (hijo-padre)
    public function rol(){
        return $this->belongsTo(Rol::class,'rol_id','id');
    }
    //pertenece a user (hijo-padre)
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
