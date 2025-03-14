<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class SubCategoria extends Model
{
    protected $table='sub_categoria';
    protected $fillable=[
        'nombre_sub_categoria',
        'categoria_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a movimiento
    public function movimiento(){
        return $this->hasMany(Movimiento::class);
    }
    //pertenece a categoria
    public function categoria(){
        return $this->belongsTo(Categoria::class,'categoria_id','id');
    }
}
