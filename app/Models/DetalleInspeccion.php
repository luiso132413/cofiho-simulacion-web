<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleInspeccion extends Model
{
    protected $connection = 'oracle';
    protected $table = 'DETALLE_INSPECCION';
    protected $primaryKey = 'id_detalle_ins';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_inspeccion',
        'componente',
        'estado',
        'observacion',
        'costo_estimado',
    ];

    public function inspeccion()
    {
        return $this->belongsTo(
            Inspeccion::class,
            'id_inspeccion',
            'id_inspeccion'
        );
    }
}
