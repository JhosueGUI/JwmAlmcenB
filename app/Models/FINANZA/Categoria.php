<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{   
    protected $table='categoria';
    protected $fillable=[
        'nombre_categoria',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a subcategoria
    public function subcategoria(){
        return $this->hasMany(SubCategoria::class);
    }
}
