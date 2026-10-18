<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $connection = 'oracle';
    protected $table = 'VENTA';
    protected $primaryKey = 'id_venta';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_cliente',
        'id_usuario',
        'id_reserva',
        'fecha_venta',
        'estado',
        'total',
    ];

    public function cliente()
    {
        return $this->belongsTo(
            Cliente::class,
            'id_cliente',
            'id_cliente'
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

    public function reserva()
    {
        return $this->belongsTo(
            Reserva::class,
            'id_reserva',
            'id_reserva'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            DetalleVenta::class,
            'id_venta',
            'id_venta'
        );
    }

    
public function transacciones()
{
    return $this->hasMany(
        TransaccionFinanciera::class,
        'id_venta',
        'id_venta'
    );
}

public function detallesFinancieros()
{
    return $this->hasMany(
        DetalleFinanciero::class,
        'id_venta',
        'id_venta'
    );
}

}
