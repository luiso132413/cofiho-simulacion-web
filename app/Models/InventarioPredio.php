<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioPredio extends Model
{
    protected $connection = 'oracle';
    protected $table = 'INVENTARIO_PREDIO';
    protected $primaryKey = 'id_inventario';

    public $timestamps = false;

    protected $fillable = [
        'FECHA_INGRESO',
        'FECHA_SALIDA',
        'ESTADO',
        'PREDIO_ID_PREDIO',
        'UBICACION_ID_UBICACION',
        'VEHICULO_ID_VEHICULO',
    ];

    public function predio()
{
    return $this->belongsTo(
        Predio::class,
        'predio_id_predio',
        'id_predio'
    );
}

public function ubicacion()
{
    return $this->belongsTo(
        Ubicacion::class,
        'ubicacion_id_ubicacion',
        'id_ubicacion'
    );
}

public function vehiculo()
{
    return $this->belongsTo(
        Vehiculo::class,
        'vehiculo_id_vehiculo',
        'id_vehiculo'
    );
}
}
