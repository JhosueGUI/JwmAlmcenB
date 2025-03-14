<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class Sustento extends Model
{
    protected $table='sustento';
    protected $fillable=[
        'nombre_sustento',
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