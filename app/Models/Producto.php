<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'precio',
        'descripcion',
        'imagen',
        'destacado',
    ];

    protected $casts = [
        'destacado' => 'boolean',
    ];

    // relación a registros intermedios (opcional)
    public function relaciones()
    {
        return $this->hasMany(CategoriaRelacion::class, 'producto_id');
    }

    // relación many-to-many usando la tabla intermedia real
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_relacion', 'producto_id', 'categoria_id');
    }

    // helper para la vista: comprobar si el producto tiene la categoría
    public function hasCategoriaTo($categoriaId)
    {
        if ($this->relationLoaded('categorias')) {
            return $this->categorias->contains('id', $categoriaId);
        }
        return $this->categorias()->where('categoria_id', $categoriaId)->exists();
    }
}
