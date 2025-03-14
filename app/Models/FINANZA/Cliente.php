<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table='cliente';
    protected $fillable=[
        'nombre_cliente',
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
