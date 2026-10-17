<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    protected $connection = 'oracle';

    protected $table = 'COLOR';

    protected $primaryKey = 'id_color';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_color',
        'nombre',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(
            Vehiculo::class,
            'color_id_color',
            'id_color'
        );
    }
}
