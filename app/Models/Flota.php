<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flota extends Model
{
    protected $table='flota';
    protected $fillable=[
        'placa',
        'personal_id',
        'tipo',
        'marca',
        'modelo',
        'empresa',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a personal(hijo-padre)
    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }
    //pertenece a combustible(padre-hijo)
    public function combustible(){
        return $this->hasMany(Combustible::class);
    }
    
}
