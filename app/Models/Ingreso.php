<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingreso';
    protected $fillable = [
        'fecha',
        'guia_remision',
        'tipo_cp',
        'documento',
        'orden_compra',
        'numero_ingreso',
        'transaccion_id',
        'estado_registro'
    ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
    //pertenece a transaccion(hijo-padre)
    public function transaccion(){
        return $this->belongsTo(Transaccion::class,'transaccion_id','id');
    }
    //pertenece a personal(hijo-padre)
    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }
}
