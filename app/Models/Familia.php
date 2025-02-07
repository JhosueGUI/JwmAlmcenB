<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table='familia';
    protected $fillable=[
        'familia',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //Le da su id  a subFamilia (padre-hijo)
    public function SubFamilia(){
        return $this->hasMany(SubFamilia::class);
    }
}
