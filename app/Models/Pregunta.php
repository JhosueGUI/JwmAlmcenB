<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'pregunta';
    protected $fillable = [
        'formulario_id',
        'pregunta',
        'estado_registro',
    ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function formulario()
    {
        return $this->belongsTo(Formulario::class, 'formulario_id', 'id');
    }
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }
}
