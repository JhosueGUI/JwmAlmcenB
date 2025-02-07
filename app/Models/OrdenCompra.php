<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $table = 'orden_compra';
    protected $fillable = [
        'fecha',
        'numero_orden',
        'sub_total',
        'IGV',
        'total',
        'url_pdf',
        'proveedor_id',
        'requerimiento',
        'gestor_compra',
        'solicitante',
        'detalle',
        'cotizacion',
        'estado_registro',
    ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //pertenece a proveedor (padre-hijo)
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'id');
    }
    //Le da su id a Orden Producto(padre-hijo)
    public function orden_producto(){
        return $this->hasMany(OrdenProducto::class);
    }
}
