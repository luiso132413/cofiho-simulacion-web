<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'oracle';
    protected $table = 'CLIENTE';
    protected $primaryKey = 'ID_CLIENTE';

    public $timestamps = false;

    protected $fillable = [
        'INGRESOS',
        'OCUPACION',
        'CLASIFICACION',
        'PERSONA_ID_PERSONA',
        'TIPO_CLIENTE_ID_TIPO_CLIENTE',
    ];

    protected $casts = [
        'INGRESOS' => 'decimal:2',
    ];

    public function persona()
    {
        return $this->belongsTo(
            Persona::class,
            'PERSONA_ID_PERSONA',
            'ID_PERSONA'
        );
    }

    public function tipoCliente()
    {
        return $this->belongsTo(
            TipoCliente::class,
            'TIPO_CLIENTE_ID_TIPO_CLIENTE',
            'ID_TIPO_CLIENTE'
        );
    }
}
