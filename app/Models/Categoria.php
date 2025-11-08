<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];

    public function relaciones()
    {
        return $this->hasMany(CategoriaRelacion::class, 'categoria_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'categoria_relacion', 'categoria_id', 'producto_id');
    }
}
