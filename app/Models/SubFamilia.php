<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubFamilia extends Model
{
    protected $table='sub_familia';
    protected $fillable=[
        'nombre',
        'familia_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    
    //pertenece a familia (hijo-padre)
    public function familia(){
        return $this->belongsTo(Familia::class,'familia_id','id');
    }
    //le da su id a articulo (padre-hijo)
    public function articulo(){
        return $this->hasMany(Articulo::class);
    }
}
