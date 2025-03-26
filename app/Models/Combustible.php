<?php

namespace App\Models;

use App\Models\COMBUSTIBLE\Grifo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combustible extends Model
{
    protected $table='combustible';
    protected $fillable=[
        'fecha',
        'destino_combustible_id',
        'personal_id',
        'flota_id',
        'grifo_id',
        'transaccion_id',
        'numero_salida_stock',
        'numero_salida_ruta',
        //
        'tipo_comprobante',
        'numero_comprobante',
        
        'precio_unitario_soles',
        'precio_total_soles',
        'precio_unitario_igv',
        'precio_total_igv',
        //
        'contometro_surtidor_inicial',
        'contometro_surtidor',
        'margen_error_surtidor',
        'resultado',
        'precinto_nuevo',
        'precinto_anterior',
        'kilometraje',
        'horometro',
        'observacion',
        'estado_registro'
    ];
    protected $primaryKey='id';
    protected $hidden=[
        'created_at','updated_at','deleted_at'
    ];
    //pertenece a DestinoCombustible
    public function destino_combustible(){
        return $this->belongsTo(DestinoCombustible::class,'destino_combustible_id','id');
    }
    //pertenece a Personal
    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }
    //pertenece a Flota
    public function flota(){
        return $this->belongsTo(Flota::class,'flota_id','id');
    }
    //pertenece a Transaccion
    public function transaccion(){
        return $this->belongsTo(Transaccion::class,'transaccion_id','id');
    }
    //pertenece a Grifo
    public function grifo(){
        return $this->belongsTo(Grifo::class,'grifo_id','id');
    }
}