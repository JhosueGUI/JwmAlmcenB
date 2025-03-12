<?php

namespace App\Models\RRHH;

use App\Models\Area;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table='cargo';
    protected $fillable=[
        'nombre_cargo',
        'area_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a area
    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }
    //le da su id a personal
    public function personal(){
        return $this->hasMany(Personal::class);
    }
}
