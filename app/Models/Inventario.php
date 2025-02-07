<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table='inventario';
    protected $fillable=[
        'producto_id',
        // 'valor_inventario',
        'total_ingreso',
        'total_salida',
        'stock_logico',
        'demanda_mensual',
        'estado_operativo_id',
        'ubicacion_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a producto (hijo-padre)
    public function producto (){
        return $this->belongsTo(Producto::class,'producto_id','id');
    }
    //pertenece a estado operativo (hijo-padre)
    public function estado_operativo (){
        return $this->belongsTo(EstadoOperativo::class,'estado_operativo_id','id');
    }
    //pertenece a ubicacion (hijo-padre)
    public function ubicacion(){
        return $this->belongsTo(Ubicacion::class,'ubicacion_id','id');
    }
    //le da su id a inventario valorizado(padre-hijo)
    public function inventario_valorizado(){
        return $this->hasMany(InventarioValorizado::class);
    }
    //le da su id a inventario detalle (padre-hijo)
    public function inventario_detalle(){
        return $this->hasMany(InvetarioDetalle::class);
    }
    
}
