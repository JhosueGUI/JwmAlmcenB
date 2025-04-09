<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'respuesta';
    protected $fillable = [
        'pregunta_id',
        'respuesta',
        'estado_registro',
    ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id', 'id');
    }
}
