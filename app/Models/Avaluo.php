<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaluo extends Model
{
    protected $connection = 'oracle';
    protected $table = 'AVALUO';
    protected $primaryKey = 'id_avaluo';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_vehiculo',
        'id_inspeccion',
        'id_usuario',
        'fecha_avaluo',
        'valor_estimado',
        'estado',
        'observaciones',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(
            Vehiculo::class,
            'id_vehiculo',
            'id_vehiculo'
        );
    }

    public function inspeccion()
    {
        return $this->belongsTo(
            Inspeccion::class,
            'id_inspeccion',
            'id_inspeccion'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            Usuario::class,
            'id_usuario',
            'id_usuario'
        );
    }
}
