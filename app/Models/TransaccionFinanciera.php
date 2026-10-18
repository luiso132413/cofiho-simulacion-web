<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaccionFinanciera extends Model
{
    protected $connection = 'oracle';
    protected $table = 'TRANSACCION_FINANCIERA';
    protected $primaryKey = 'id_transaccion';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_reserva',
        'id_venta',
        'id_metodo_pago',
        'tipo',
        'estado',
        'monto',
        'moneda',
        'referencia_externa',
        'fecha_transaccion',
    ];

    public function venta()
    {
        return $this->belongsTo(
            Venta::class,
            'id_venta',
            'id_venta'
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

    public function metodoPago()
    {
        return $this->belongsTo(
            MetodoPago::class,
            'id_metodo_pago',
            'id_metodo_pago'
        );
    }
}
