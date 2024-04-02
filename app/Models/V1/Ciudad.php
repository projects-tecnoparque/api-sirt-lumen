<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $fillable = [
        'nombre', 'departamento_id'
    ];
}
