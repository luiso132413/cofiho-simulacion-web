<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Predio extends Model
{
    protected $connection = 'oracle';

    protected $table = 'PREDIO';

    protected $primaryKey = 'id_predio';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_predio',
        'nombre',
        'direccion',
        'tipo',
        'capacidad',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(
            Vehiculo::class,
            'predio_id_predio',
            'id_predio'
        );
    }

    public function ubicaciones()
{
    return $this->hasMany(
        Ubicacion::class,
        'predio_id_predio',
        'id_predio'
    );
}

public function inventarios()
{
    return $this->hasMany(
        InventarioPredio::class,
        'predio_id_predio',
        'id_predio'
    );
}
}
