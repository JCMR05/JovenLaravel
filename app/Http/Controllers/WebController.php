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
        
        // Productos destacados (solo los marcados como destacado)
        $productosDestacados = Producto::with('categorias')
            ->where('destacado', true)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

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

            return view('web.index', compact('productos', 'categoriasFiltro', 'productosDestacados', 'search', 'sort', 'selectedCategories'));
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

        return view('web.index', compact('categorias', 'categoriasFiltro', 'productosDestacados', 'search', 'sort', 'selectedCategories'));
    }

    public function show($id)
    {
        $producto = Producto::with('categorias')->findOrFail($id);
        return view('web.item', compact('producto'));
    }

    public function tienda(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'newest');
        $categoriaId = $request->input('categoria');

        $categorias = Categoria::orderBy('nombre')->get();

        $productosQuery = Producto::with('categorias');

        // Filtro por búsqueda
        if (!empty($search)) {
            $productosQuery->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if (!empty($categoriaId)) {
            $productosQuery->whereHas('categorias', function($q) use ($categoriaId) {
                $q->where('categorias.id', $categoriaId);
            });
        }

        // Ordenamiento
        switch ($sort) {
            case 'priceAsc':
                $productosQuery->orderBy('precio', 'asc');
                break;
            case 'priceDesc':
                $productosQuery->orderBy('precio', 'desc');
                break;
            case 'nameAsc':
                $productosQuery->orderBy('nombre', 'asc');
                break;
            case 'nameDesc':
                $productosQuery->orderBy('nombre', 'desc');
                break;
            default:
                $productosQuery->orderBy('id', 'desc');
        }

        $productos = $productosQuery->paginate(12);

        return view('web.tienda', compact('productos', 'categorias', 'search', 'sort', 'categoriaId'));
    }
}