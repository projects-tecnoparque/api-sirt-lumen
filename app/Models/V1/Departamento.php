<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class Departamento extends Model
{
    /**
     * Obtenga todos las ciudades que pertenecen al departamento.
     */
    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'departamento_id', 'id');
    }

    /**
     * Buscar un departamento a partir de una cadena.
     */
    public static function findFromString(string $value)
    {
        return static::query()
            ->where(function ($query) use ($value) {
                $query->where("nombre", $value);
            })
            ->first();
    }

    /**
     * Buscar un departamento a partir de una entero.
     */
    public static function findFromInt(int $value)
    {
        return static::query()
            ->where(function ($query) use ($value) {
                $query->where("id", $value);
            })
            ->first();
    }

    /**
     * Scope para obtener los departamentos que contienen el nombre ingresado.
     */
    public function scopeContainingForName(Builder $query, string $name): Builder
    {
        return $query->whereRaw('nombre like ?', ['%' . mb_strtolower($name) . '%']);
    }

    /**
     * Encuentre una departamento por su nombre.
     */
    public static function findByName(string $name)
    {
        $model = static::findByParam(['nombre' => $name]);
        if (! $model) {
            throw new InvalidArgumentException("No results found for name: {$name} in ". basename(static::class));
        }
        return $model;
    }
}
