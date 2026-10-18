<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenVehiculo extends Model
{
    protected $connection = 'oracle';

    protected $table = 'IMAGEN_VEHICULO';

    protected $primaryKey = 'id_imagen';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_imagen',
        'url',
        'orden',
        'es_principal',
        'vehiculo_id_vehiculo',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(
            Vehiculo::class,
            'vehiculo_id_vehiculo',
            'id_vehiculo'
        );
    }
}
