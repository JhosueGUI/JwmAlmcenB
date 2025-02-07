<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinoCombustible extends Model
{
    protected $table='destino_combustible';
    protected $fillable=[
        'nombre',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a Combustible
    public function combustible(){
        $this->hasMany(Combustible::class);
    }
}