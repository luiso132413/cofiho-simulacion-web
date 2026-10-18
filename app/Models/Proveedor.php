<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    protected $connection = 'oracle';

    protected $table = 'PROVEEDOR';

    protected $primaryKey = 'id_proveedor';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'nombre',
        'tipo',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(
            Vehiculo::class,
            'proveedor_id_proveedor',
            'id_proveedor'
        );
    }
}
