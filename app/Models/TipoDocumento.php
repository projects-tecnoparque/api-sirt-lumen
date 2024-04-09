<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tiposdocumentos';

    protected $fillable = [
        'abreviatura', 'nombre', 'estado'
    ];
}
