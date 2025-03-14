<?php

namespace App\Models\FINANZA;

use App\Models\Persona;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table='movimiento';
    protected $fillable=[
        'fecha',
        'n_operacion',
        'ingreso',
        'egreso',
        'descripcion',
        'solicitante',
        'sub_destino_placa',
        'serie',
        'n_factura',
        'fecha_factura',
        'obs',
        'n_retencion',
        'fecha_retencion',
        'modo_id',
        'cliente_id',
        'persona_id',
        'proveedor_id',
        'sub_categoria_id',
        'empresa_id',
        'estado_id',
        'rendicion_id',
        'sustento_id',
        'moneda_id',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a modo
    public function modo(){
        return $this->belongsTo(Modo::class,'modo_id','id');
    }
    //pertenece a proveedor
    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }
    //pertenece a sub_categoria
    public function sub_categoria(){
        return $this->belongsTo(SubCategoria::class,'sub_categoria_id','id');
    }
    //pertenece a empresa
    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
    //pertenece a estado
    public function estado(){
        return $this->belongsTo(EstadoComprobante::class,'estado_id','id');
    }
    //pertenece a rendicion
    public function rendicion(){
        return $this->belongsTo(Rendicion::class,'rendicion_id','id');
    }
    //pertenece a sustento
    public function sustento(){
        return $this->belongsTo(Sustento::class,'sustento_id','id');
    }
    //pertenece a moneda
    public function moneda(){
        return $this->belongsTo(Moneda::class,'moneda_id','id');
    }
    //pertenece a persona
    public function persona(){
        return $this->belongsTo(Persona::class,'persona_id','id');
    }
    //pertenece a proveedor
    public function proveedor(){
        return $this->belongsTo(Proveedor::class,'proveedor_id','id');
    }
}