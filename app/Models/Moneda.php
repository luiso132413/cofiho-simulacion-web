<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Moneda extends Model
{
    protected $connection = 'oracle';

    protected $table = 'MONEDA';

    protected $primaryKey = 'id_moneda';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_moneda',
        'codigo',
        'nombre',
        'tipo_cambio',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(
            Vehiculo::class,
            'moneda_id_moneda',
            'id_moneda'
        );
    }
}
