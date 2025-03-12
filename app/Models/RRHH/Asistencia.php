<?php

namespace App\Models\RRHH;

use App\Models\Personal;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table='asistencia';
    protected $fillable=[
        'persona_id',
        'fecha_asistencia',
        'dia_asistencia',
        'hora_ingreso',
        'hora_salida',
        'tiempo_total',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a personal
    public function personal(){
        return $this->belongsTo(Personal::class,'persona_id','id');
    }
}
