<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table='empresa';
    protected $fillable=[
        'nombre_empresa',
        'total_ingreso_soles',
        'total_egreso_soles',
        'total_ingreso_dolares',
        'total_egreso_dolares',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a movimiento
    public function movimiento(){
        return $this->hasMany(Movimiento::class);
    }
}
