<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table='personal';
    protected $fillable=[
        'persona_id',
        'area_id',
        'habilidad',
        'experiencia',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a persona(hijo-padre)
    public function persona(){
        return $this->belongsTo(Persona::class,'persona_id','id');
    }
    //le da su id a user(padre-hijo)
    public function user(){
        return $this->hasMany(User::class);
    }
    //le da su id a ingreso(padre-hijo)
    public function ingreso(){
        return $this->hasMany(Proveedor::class);
    }
    //le da su id a salida(padre-hijo)
    public function salida(){
        return $this->hasMany(Salida::class);
    }
    //le da su id a flota (padre-hijo)
    public function flota(){
        return $this->hasMany(Flota::class);
    }
    //le da su id a combustible(padre-hijo)
    public function combustible(){
        return $this->hasMany(Combustible::class);
    }
    //pertenece a area (hijo-padre)
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }
}

