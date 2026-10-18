<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $connection = 'oracle';
    protected $table = 'VEHICULO';
    protected $primaryKey = 'id_vehiculo';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'vin',
        'motor',
        'placa',
        'anio',
        'id_modelo',
        'id_tipo_vehiculo',
        'color',
        'condicion',
        'estado',
        'precio_lista',
        'fecha_registro',
    ];

    public function modelo()
    {
        return $this->belongsTo(
            Modelo::class,
            'id_modelo',
            'id_modelo'
        );
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(
            TipoVehiculo::class,
            'id_tipo_vehiculo',
            'id_tipo_vehiculo'
        );
    }

    public function inventarios()
    {
        return $this->hasMany(
            InventarioPredio::class,
            'id_vehiculo',
            'id_vehiculo'
        );
    }
}
