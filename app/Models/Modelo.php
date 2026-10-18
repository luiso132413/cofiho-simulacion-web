<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $connection = 'oracle';
    protected $table = 'MODELO';
    protected $primaryKey = 'id_modelo';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_marca',
        'nombre',
    ];

    public function marca()
    {
        return $this->belongsTo(
            Marca::class,
            'id_marca',
            'id_marca'
        );
    }

    public function vehiculos()
    {
        return $this->hasMany(
            Vehiculo::class,
            'id_modelo',
            'id_modelo'
        );
    }
}
