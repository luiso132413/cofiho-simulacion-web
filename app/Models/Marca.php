<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $connection = 'oracle';
    protected $table = 'MARCA';
    protected $primaryKey = 'id_marca';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
    ];

    public function modelos()
    {
        return $this->hasMany(
            Modelo::class,
            'id_marca',
            'id_marca'
        );
    }
}
