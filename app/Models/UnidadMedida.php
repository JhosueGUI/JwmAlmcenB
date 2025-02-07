<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table='unidad_medida';
    protected $fillable=[
        'nombre',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    // le da su id a producto (padre-hijo)
    public function producto(){
        return $this->hasMany(Producto::class);
    }
}
