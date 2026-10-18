<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $connection = 'oracle';
    protected $table = 'ROL';
    protected $primaryKey = 'id_rol';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    public function usuarios()
    {
        return $this->hasMany(
            Usuario::class,
            'id_rol',
            'id_rol'
        );
    }
}
