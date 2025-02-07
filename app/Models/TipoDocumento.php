<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table='tipo_documento';
    protected $fillable=[
        'nombre',
        'descripcion',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a persona(padre-hijo)
    public function persona(){
        return $this->hasMany(Persona::class);
    }
}
