<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $connection = 'oracle';

    protected $table = 'PERSONA';

    protected $primaryKey = 'id_persona';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'dpi',
        'telefono',
    ];

    public function usuario()
    {
        return $this->hasOne(
            Usuario::class,
            'id_persona',
            'id_persona'
        );
    }
}