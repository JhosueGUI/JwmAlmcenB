<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $table = 'formulario';
    protected $fillable = [
        'personal_id',
        'titulo',
        'descripcion',
        'url_pdf',
        'estado_registro',
    ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id', 'id');
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }
}
