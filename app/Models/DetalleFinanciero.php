<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFinanciero extends Model
{
    protected $connection = 'oracle';
    protected $table = 'DETALLE_FINANCIERO';
    protected $primaryKey = 'id_detalle_fin';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_venta',
        'concepto',
        'descripcion',
        'monto',
    ];

    public function venta()
    {
        return $this->belongsTo(
            Venta::class,
            'id_venta',
            'id_venta'
        );
    }
}
