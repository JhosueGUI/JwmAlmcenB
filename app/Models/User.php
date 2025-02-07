<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username',
        'password',
        'personal_id',
        'estado_registro'
    ];
    protected $hidden = [
        'password',
        'persona_id',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //le da su id a user_rol(padre-hijo)
    public function user_rol()
    {
        return $this->hasMany(UsuarioRol::class);
    }
    //pertenece a personal (hijo-padre)
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id', 'id');
    }

    // Métodos para JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    //hashear el password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    //para obtener el usuario_rol()
    public function usuario_rol()
    {
        return $this->hasMany(UsuarioRol::class, 'user_id', 'id');
    }
    //para obtner el rol()
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'user_id', 'rol_id');
    }
    //para obtener persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }
    //para obtner acceso()
    public function accesos(){
        return $this->belongsToMany(Acceso::class,'acceso_id','user_id','id');
    }
}
