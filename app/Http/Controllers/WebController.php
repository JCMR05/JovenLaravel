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

        // Si hay categorías seleccionadas por el usuario, mostrar solo esas categorías (aunque no tengan productos)
        if (!empty($selectedCategories)) {
            $categorias = Categoria::whereIn('id', (array) $selectedCategories)
                ->orderBy('nombre')
                ->paginate(4);
        } else {
            // traer categorías que tengan productos (filtrados por búsqueda si aplica)
            $categorias = Categoria::whereHas('productos', function($q) use ($search) {
                    if (!empty($search)) {
                        $q->where('nombre', 'like', "%{$search}%");
                    }
                })
                ->orderBy('nombre')
                ->paginate(4); // <- límite de 5 categorías por página
        }

        // para cada categoría cargar sus productos paginados (10 por página)
        foreach ($categorias as $categoria) {
            $productosQuery = $categoria->productos()
                ->when(!empty($search), function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%");
                });

            // Además, si se seleccionaron categorías, ya estamos iterando solo las seleccionadas.
            // No hace falta filtrar productos por categoría aquí porque usamos la relación del modelo.

            if ($sort === 'priceAsc') {
                $productosQuery->orderBy('precio', 'asc');
            } elseif ($sort === 'priceDesc') {
                $productosQuery->orderBy('precio', 'desc');
            } else {
                $productosQuery->orderBy('id', 'desc');
            }

            // paginador por categoría; usa un nombre de página distinto para cada categoría
            $pageName = 'page_cat_' . $categoria->id;
            $categoria->setRelation('productos', $productosQuery->paginate(4, ['*'], $pageName));
        }

        // además pasar todas las categorías para el panel de filtros (incluye categorías sin productos)
        $categoriasFiltro = Categoria::orderBy('nombre')->get();

        return view('web.index', compact('categorias', 'categoriasFiltro'));
    }

    public function show($id){
        // Obtener el producto por ID
        $producto = Producto::findOrFail($id);
        // Pasar el producto a la vista
        return view('web.item', compact('producto'));
    }
}