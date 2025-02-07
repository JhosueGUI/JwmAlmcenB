<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvetarioDetalle extends Model
{
    protected $table='inventario_detalle';
    protected $fillable=[
        'inventario_id',
        'demanda_media_mensual',
        'demanda_minima_mensual',
        'demanda_maxima_mensual',
        'demanda_mensual',
        'demanda_media_diaria',
        'demanda_minima_diaria',
        'demanda_maxima_diaria',
        'lead_time',
        'stock_minimo',
        'stock_maximo',
        'punto_pedido',
        'lote_minimo',
        'pedido_ajustado',
        'valor_pedido_ajustado',
        'estado_registro',
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a inventario(hijo-padre)
    public function inventario(){
        return $this->belongsTo(Inventario::class,'inventario_id','id');
    }
}
