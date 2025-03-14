<?php

namespace App\Models;

use App\Models\FINANZA\Movimiento;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table='proveedor';
    protected $fillable=[
        'razon_social',
        'ruc',
        'direccion',
        'forma_pago',
        'contacto',
        // 'persona_id',
        'numero_celular',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    // //Pertenece a Persona (padre-hijo)
    // public function persona(){
    //     return $this->belongsTo(Persona::class,'persona_id','id');
    // }

    //le da su id a proveedor_producto(padre-hijo)
    public function proveedor_producto(){
        return $this->hasMany(Proveedor::class);
    }
    //le da su id a movimiento
    public function movimiento(){
        return $this->hasMany(Movimiento::class);
    }
}
