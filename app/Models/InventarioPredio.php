<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioPredio extends Model
{
    protected $connection = 'oracle';
    protected $table = 'INVENTARIO_PREDIO';
    protected $primaryKey = 'id_inventario';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_vehiculo',
        'id_predio',
        'ubicacion',
        'fecha_ingreso',
        'fecha_salida',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(
            Vehiculo::class,
            'id_vehiculo',
            'id_vehiculo'
        );
    }

    public function predio()
    {
        return $this->belongsTo(
            Predio::class,
            'id_predio',
            'id_predio'
        );
    }
}
