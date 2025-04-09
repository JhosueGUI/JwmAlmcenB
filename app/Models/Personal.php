<?php

namespace App\Models;

use App\Models\RRHH\Asistencia;
use App\Models\RRHH\Cargo;
use App\Models\RRHH\Planilla;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table='personal';
    protected $fillable=[
        'cargo_id',
        'planilla_id',
        'persona_id',
        'habilidad',
        'experiencia',
        'fecha_ingreso',
        'fecha_ingreso_planilla',
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
    //pertenece a planilla (hijo-padre)
    public function planilla(){
        return $this->belongsTo(Planilla::class,'planilla_id','id');
    }
    //le da su id a asistencia(padre-hijo)
    public function asistencia(){
        return $this->hasMany(Asistencia::class);
    }
    //pertenece a cargo(hijo-padre)
    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }
    //le da su id a formulario(padre-hijo)
    public function formulario(){
        return $this->hasMany(Formulario::class);
    }
}

