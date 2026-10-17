<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoVehiculo extends Model
{
    protected $connection = 'oracle';

    protected $table = 'TIPOS_VEHICULO';

    protected $primaryKey = 'id_tipo';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_tipo',
        'nombre',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(
            Vehiculo::class,
            'tipos_vehiculo_id_tipo',
            'id_tipo'
        );
    }
}
