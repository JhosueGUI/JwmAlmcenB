<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='area';
    protected $fillable=[
        'nombre',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //le da su id a Personal
    public function personal(){
        return $this->hasMany(Personal::class);
    }
}
