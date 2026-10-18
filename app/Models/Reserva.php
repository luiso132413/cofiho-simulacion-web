<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $connection = 'oracle';
    protected $table = 'RESERVA';
    protected $primaryKey = 'id_reserva';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_cliente',
        'id_vehiculo',
        'id_usuario',
        'canal',
        'fecha_reserva',
        'fecha_expiracion',
        'estado',
        'monto_enganche',
    ];

    public function cliente()
    {
        return $this->belongsTo(
            Cliente::class,
            'id_cliente',
            'id_cliente'
        );
    }

    public function vehiculo()
    {
        return $this->belongsTo(
            Vehiculo::class,
            'id_vehiculo',
            'id_vehiculo'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            Usuario::class,
            'id_usuario',
            'id_usuario'
        );
    }
}
