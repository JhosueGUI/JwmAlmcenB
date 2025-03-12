<?php

namespace App\Models;

use App\Models\RRHH\Cargo;
use App\Models\RRHH\Horario;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='area';
    protected $fillable=[
        'nombre',
        'horario_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a horario
    public function horario(){
        return $this->belongsTo(Horario::class,'horario_id','id');
    }
    //le da su id a cargo
    public function cargo(){
        return $this->hasMany(Cargo::class);
    }
}
