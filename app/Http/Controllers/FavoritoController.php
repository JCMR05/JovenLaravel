<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    /**
     * Mostrar lista de favoritos del usuario
     */
    public function index()
    {
        $favoritos = Auth::user()->productosFavoritos()
            ->with('categorias')
            ->orderBy('favoritos.created_at', 'desc')
            ->get();

        return view('web.favoritos', compact('favoritos'));
    }

    /**
     * Toggle: agregar o quitar de favoritos
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id'
        ]);

        $userId = Auth::id();
        $productoId = $request->producto_id;

        $favorito = Favorito::where('user_id', $userId)
            ->where('producto_id', $productoId)
            ->first();

        if ($favorito) {
            $favorito->delete();
            $isFavorito = false;
            $mensaje = 'Producto eliminado de favoritos';
        } else {
            Favorito::create([
                'user_id' => $userId,
                'producto_id' => $productoId,
            ]);
            $isFavorito = true;
            $mensaje = 'Producto añadido a favoritos';
        }

        // Si es una petición AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'isFavorito' => $isFavorito,
                'mensaje' => $mensaje,
                'totalFavoritos' => Auth::user()->favoritos()->count()
            ]);
        }

        return back()->with('mensaje', $mensaje);
    }

    /**
     * Eliminar de favoritos
     */
    public function destroy($productoId)
    {
        $favorito = Favorito::where('user_id', Auth::id())
            ->where('producto_id', $productoId)
            ->first();

        if ($favorito) {
            $favorito->delete();
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Producto eliminado de favoritos',
                'totalFavoritos' => Auth::user()->favoritos()->count()
            ]);
        }

        return back()->with('mensaje', 'Producto eliminado de favoritos');
    }

    /**
     * Limpiar todos los favoritos
     */
    public function limpiar()
    {
        Auth::user()->favoritos()->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Todos los favoritos han sido eliminados'
            ]);
        }

        return back()->with('mensaje', 'Todos los favoritos han sido eliminados');
    }
}