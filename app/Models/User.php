<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // Incluye los campos que asignas al registrar
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',       // <- importante
        'is_admin',  // si existe en tu tabla
        'puntos',  // Agregar puntos
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'puntos' => 'integer',
    ];

    public function entradas(){
        return $this->hasMany(Entrada::class);
    }

    // Relación con pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    // Relación con favoritos
    public function favoritos()
    {
        return $this->hasMany(Favorito::class);
    }

    // Productos favoritos del usuario
    public function productosFavoritos()
    {
        return $this->belongsToMany(Producto::class, 'favoritos', 'user_id', 'producto_id')->withTimestamps();
    }

    // Verificar si un producto está en favoritos
    public function tieneEnFavoritos($productoId)
    {
        return $this->favoritos()->where('producto_id', $productoId)->exists();
    }

    /**
     * Calcular puntos a otorgar basado en el monto
     * 1 punto por cada $100
     */
    public static function calcularPuntos($monto)
    {
        return (int) floor($monto / 100);
    }

    /**
     * Agregar puntos al usuario
     */
    public function agregarPuntos($puntos)
    {
        $this->increment('puntos', $puntos);
        return $this->puntos;
    }

    /**
     * Restar puntos al usuario (para canjear)
     */
    public function restarPuntos($puntos)
    {
        if ($this->puntos >= $puntos) {
            $this->decrement('puntos', $puntos);
            return true;
        }
        return false;
    }
}
