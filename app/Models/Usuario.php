<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
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

    public function rol()
{
    return $this->belongsTo(
        Rol::class,
        'id_rol',
        'id_rol'
    );
}
}
