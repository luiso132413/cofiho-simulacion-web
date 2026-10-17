<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modelo extends Model
{
    protected $connection = 'oracle';

    protected $table = 'MODELO';

    protected $primaryKey = 'id_modelo';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_modelo',
        'nombre',
        'anio_vigencia',
        'marca_id_marca',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(
            Marca::class,
            'marca_id_marca',
            'id_marca'
        );
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(
            Vehiculo::class,
            'modelo_id_modelo',
            'id_modelo'
        );
    }

    
}
