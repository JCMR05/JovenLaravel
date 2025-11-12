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

        // Filtro para panel de categorías (todos los items para opciones)
        $categoriasFiltro = Categoria::orderBy('nombre')->get();

        // Si hay término de búsqueda, mostrar solo productos (no categorías)
        if (!empty($search)) {
            $productosQuery = Producto::with('categorias')
                ->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
                });

            // Orden
            if ($sort === 'priceAsc') {
                $productosQuery->orderBy('precio', 'asc');
            } elseif ($sort === 'priceDesc') {
                $productosQuery->orderBy('precio', 'desc');
            } else {
                $productosQuery->orderBy('id', 'desc');
            }

            // Mostrar 8 productos por página (4 más que antes)
            $productos = $productosQuery->paginate(8)->appends($request->query());

            // Pasar productos a la vista; no pasamos $categorias para ocultar la vista por categorías
            return view('web.index', compact('productos', 'categoriasFiltro', 'search', 'sort', 'selectedCategories'));
        }

        // Sin búsqueda: mostrar categorías con sus productos paginados por categoría (4 por página)
        if (!empty($selectedCategories)) {
            $categorias = Categoria::whereIn('id', (array) $selectedCategories)
                ->orderBy('nombre')
                ->paginate(4);
        } else {
            $categorias = Categoria::whereHas('productos', function($q) use ($search) {
                    if (!empty($search)) {
                        $q->where('nombre', 'like', "%{$search}%");
                    }
                })
                ->orderBy('nombre')
                ->paginate(4);
        }

        foreach ($categorias as $categoria) {
            $productosQuery = $categoria->productos()
                ->when(!empty($search), function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%");
                });

            if ($sort === 'priceAsc') {
                $productosQuery->orderBy('precio', 'asc');
            } elseif ($sort === 'priceDesc') {
                $productosQuery->orderBy('precio', 'desc');
            } else {
                $productosQuery->orderBy('id', 'desc');
            }

            $pageName = 'page_cat_' . $categoria->id;
            $categoria->setRelation('productos', $productosQuery->paginate(4, ['*'], $pageName));
        }

        return view('web.index', compact('categorias', 'categoriasFiltro', 'search', 'sort', 'selectedCategories'));
    }

    public function show($id){
        // Obtener el producto por ID
        $producto = Producto::findOrFail($id);
        // Pasar el producto a la vista
        return view('web.item', compact('producto'));
    }
}