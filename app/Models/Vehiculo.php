<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehiculo extends Model
{
    protected $connection = 'oracle';

    protected $table = 'VEHICULO';

    protected $primaryKey = 'id_vehiculo';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'vin',
        'motor',
        'placa',
        'anio',
        'precio',
        'estado',
        'estado_comercial',
        'modelo_id_modelo',
        'tipos_vehiculo_id_tipo',
        'color_id_color',
        'predio_id_predio',
        'proveedor_id_proveedor',
        'moneda_id_moneda',
    ];

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(
            Modelo::class,
            'modelo_id_modelo',
            'id_modelo'
        );
    }

    public function tipoVehiculo(): BelongsTo
    {
        return $this->belongsTo(
            TipoVehiculo::class,
            'tipos_vehiculo_id_tipo',
            'id_tipo'
        );
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(
            Color::class,
            'color_id_color',
            'id_color'
        );
    }

    public function predio(): BelongsTo
    {
        return $this->belongsTo(
            Predio::class,
            'predio_id_predio',
            'id_predio'
        );
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(
            Proveedor::class,
            'proveedor_id_proveedor',
            'id_proveedor'
        );
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(
            Moneda::class,
            'moneda_id_moneda',
            'id_moneda'
        );
    }
}
