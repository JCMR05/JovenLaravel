<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaRelacion extends Model
{
    protected $fillable = ['categoria_id', 'producto_id'];

    public function pedido()
    {
        return $this->belongsTo(Categoria::class);
    }
    
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
