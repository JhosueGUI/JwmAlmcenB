<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class EstadoComprobante extends Model
{
    protected $table='estado_comprobante';
    protected $fillable=[
        'nombre_estado_comprobante',
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
