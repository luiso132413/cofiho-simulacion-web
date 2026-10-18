<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $connection = 'oracle';
    protected $table = 'DETALLE_VENTA';
    protected $primaryKey = 'id_detalle_venta';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_venta',
        'id_vehiculo',
        'precio_lista',
        'precio_venta',
    ];

    public function venta()
    {
        return $this->belongsTo(
            Venta::class,
            'id_venta',
            'id_venta'
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
}
