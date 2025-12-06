<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;

class WebController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort');
        $selectedCategories = $request->input('categories', []);

        $categoriasFiltro = Categoria::orderBy('nombre')->get();

        // Búsqueda
        if (!empty($search)) {
            $productosQuery = Producto::with('categorias')
                ->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
                });

            if ($sort === 'priceAsc') $productosQuery->orderBy('precio', 'asc');
            elseif ($sort === 'priceDesc') $productosQuery->orderBy('precio', 'desc');
            else $productosQuery->orderBy('id', 'desc');

            $productos = $productosQuery->take(20)->get();

            return view('web.index', compact('productos', 'categoriasFiltro', 'search', 'sort', 'selectedCategories'));
        }

        // Categorías
        if (!empty($selectedCategories)) {
            $categorias = Categoria::whereIn('id', (array) $selectedCategories)->orderBy('nombre')->get();
        } else {
            $categorias = Categoria::whereHas('productos')->orderBy('nombre')->get();
        }

        foreach ($categorias as $categoria) {
            $q = $categoria->productos();
            if ($sort === 'priceAsc') $q->orderBy('precio', 'asc');
            elseif ($sort === 'priceDesc') $q->orderBy('precio', 'desc');
            else $q->orderBy('id', 'desc');
            $categoria->setRelation('productos', $q->take(15)->get());
        }

        return view('web.index', compact('categorias', 'categoriasFiltro', 'search', 'sort', 'selectedCategories'));
    }

    public function show($id)
    {
        $producto = Producto::with('categorias')->findOrFail($id);
        return view('web.show', compact('producto'));
    }
}