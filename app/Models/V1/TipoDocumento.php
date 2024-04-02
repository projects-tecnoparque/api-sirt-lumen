<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = [
        'abreviatura', 'nombre', 'estado'
    ];
}
