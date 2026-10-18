<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $connection = 'oracle';
    protected $table = 'UBICACION';
    protected $primaryKey = 'id_ubicacion';

    public $timestamps = false;

    protected $fillable = [
        'NOMBRE',
        'TIPO',
        'PREDIO_ID_PREDIO',
    ];

    public function predio()
    {
        return $this->belongsTo(
            Predio::class,
            'PREDIO_ID_PREDIO',
            'ID_PREDIO'
        );
    }

    public function inventarios()
    {
        return $this->hasMany(
            InventarioPredio::class,
            'UBICACION_ID_UBICACION',
            'ID_UBICACION'
        );
    }
}
