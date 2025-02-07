<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrdenProducto extends Model
{
    protected $table = 'orden_producto';
    protected $fillable = [
        'producto_id',
        'orden_compra_id',
        'cantidad',
        'precio_soles',
        'precio_dolares',
        'estado_registro'
    ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //pertence a producto (hijo-padre)
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
    //pertence a orden de compra (hijo-padre)
    public function orden_compra()
    {
        return $this->belongsTo(OrdenCompra::class, 'orden_compra_id', 'id');
    }
    public function getPdfUrl($value)
    {
        if ($value) {
            // La ruta es relativa a 'storage/app/public'
            return url(Storage::url('orden_compra/pdf/' . $value));
        }
        return null; // Retorna null si $value está vacío o no es válido
    }
}
