<?php

namespace App\Models\RRHH;

use App\Models\Personal;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    protected $table='planilla';
    protected $fillable=[
        'nombre_planilla',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a personal
    public function personal(){
        return $this->hasMany(Personal::class);
    }
}
