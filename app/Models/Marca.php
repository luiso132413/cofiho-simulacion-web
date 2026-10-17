<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    protected $connection = 'oracle';

    protected $table = 'MARCA';

    protected $primaryKey = 'id_marca';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_marca',
        'nombre',
    ];

    public function modelos(): HasMany
    {
        return $this->hasMany(
            Modelo::class,
            'marca_id_marca',
            'id_marca'
        );
    }
}
