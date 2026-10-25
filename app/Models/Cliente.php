<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'oracle';
    protected $table = 'CLIENTE';
    protected $primaryKey = 'id_cliente';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_cliente',
        'dpi',
        'nit',
        'email',
        'telefono',
        'direccion',
        'fecha_registro',
    ];

    protected $casts = [
        'activo' => 'integer',
    ];
}
