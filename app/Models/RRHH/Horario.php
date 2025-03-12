<?php

namespace App\Models\RRHH;

use App\Models\Area;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table='horario';
    protected $fillable=[
        'horario_estandar',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id al area (padre-hijo)
    public function area(){
        return $this->hasMany(Area::class);
    }
}
