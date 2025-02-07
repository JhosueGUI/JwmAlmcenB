<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $table='salida';
    protected $fillable=[
        'fecha',
        'vale',
        'destino',
        'personal_id',
        'unidad',
        'duracion_neumatico',
        'kilometraje_horometro',
        'fecha_vencimiento',
        'numero_salida',
        'transaccion_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a personal (hijo-padre)
    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }
    //pertenece a transaccion (hijo-padre)
    public function transaccion(){
        return $this->belongsTo(Transaccion::class,'transaccion_id','id');
    }
}
