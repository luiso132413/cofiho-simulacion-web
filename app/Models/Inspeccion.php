<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
    protected $connection = 'oracle';
    protected $table = 'INSPECCION';
    protected $primaryKey = 'id_inspeccion';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_vehiculo',
        'id_usuario',
        'fecha_inspeccion',
        'resultado',
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

    public function usuario()
    {
        return $this->belongsTo(
            Usuario::class,
            'id_usuario',
            'id_usuario'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            DetalleInspeccion::class,
            'id_inspeccion',
            'id_inspeccion'
        );
    }

    public function avaluos()
    {
        return $this->hasMany(
            Avaluo::class,
            'id_inspeccion',
            'id_inspeccion'
        );
    }
}
