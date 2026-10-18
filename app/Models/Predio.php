<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Predio extends Model
{
    protected $connection = 'oracle';
    protected $table = 'PREDIO';
    protected $primaryKey = 'id_predio';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'direccion',
        'tipo',
        'activo',
    ];

    public function inventarios()
    {
        return $this->hasMany(
            InventarioPredio::class,
            'id_predio',
            'id_predio'
        );
    }
}
