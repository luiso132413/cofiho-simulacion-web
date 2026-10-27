<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $connection = 'oracle';
    protected $table = 'USUARIO';
    protected $primaryKey = 'id_usuario';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $hidden = [
        'password_hash',
    ];

    protected $fillable = [
        'id_rol',
        'nombre',
        'email',
        'password_hash',
        'activo',
        'fecha_creacion',
    ];

    /**
     * Campo que contiene el hash de la contraseña.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function rol()
    {
        return $this->belongsTo(
            Rol::class,
            'id_rol',
            'id_rol'
        );
    }

     public function persona()
    {
        return $this->belongsTo(
            Persona::class,
            'id_persona',
            'id_persona'
        );
    }

}
