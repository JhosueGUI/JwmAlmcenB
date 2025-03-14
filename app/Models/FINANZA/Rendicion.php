<?php

namespace App\Models\FINANZA;

use Illuminate\Database\Eloquent\Model;

class Rendicion extends Model
{
    protected $table='rendicion';
    protected $fillable=[
        'nombre_rendicion',
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
}
