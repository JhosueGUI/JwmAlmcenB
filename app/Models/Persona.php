<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table='persona';
    protected $fillable=[
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'gmail',
        'tipo_documento_id',
        'numero_documento',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a tipo_documento(hijo-padre)
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class,'tipo_documento_id','id');
    }
    
    //le da su id a personal(padre-hijo)
    public function personal(){
        return $this->hasMany(Personal::class);
    }
    
    // //le da su id a Proveedor (padre-hijo)
    // public function proveedor(){
    //     return $this->hasMany(Proveedor::class);
    // }
}
