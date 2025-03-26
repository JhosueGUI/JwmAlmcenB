<?php

namespace App\Models\COMBUSTIBLE;

use App\Models\Combustible;
use Illuminate\Database\Eloquent\Model;

class Grifo extends Model
{
    protected $table='grifo';
    protected $fillable=[
        'ruc',
        'nombre',
        'direccion',
        'distrito',
        'provincia',
        'departamento',
        'telefono',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a combustible
    public function combustible(){
        return $this->hasMany(Combustible::class);
    }
}
