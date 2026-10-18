<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    protected $connection = 'oracle';
    protected $table = 'TIPO_VEHICULO';
    protected $primaryKey = 'id_tipo_vehiculo';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
    ];

    public function vehiculos()
    {
        return $this->hasMany(
            Vehiculo::class,
            'id_tipo_vehiculo',
            'id_tipo_vehiculo'
        );
    }
}
