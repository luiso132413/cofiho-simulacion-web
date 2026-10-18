<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $connection = 'oracle';
    protected $table = 'METODO_PAGO';
    protected $primaryKey = 'id_metodo_pago';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'tipo',
        'activo',
    ];

    public function transacciones()
    {
        return $this->hasMany(
            TransaccionFinanciera::class,
            'id_metodo_pago',
            'id_metodo_pago'
        );
    }
}
