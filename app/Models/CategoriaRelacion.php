<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaRelacion extends Model
{
    // nombre de tabla real
    protected $table = 'categoria_relacion';

    // si la tabla NO tiene columnas created_at/updated_at:
    public $timestamps = true;

    protected $fillable = ['categoria_id', 'producto_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
