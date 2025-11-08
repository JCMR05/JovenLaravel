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

        // traer categorías que tengan productos (filtrados por búsqueda si aplica)
        $categorias = Categoria::whereHas('productos', function($q) use ($search) {
                if (!empty($search)) {
                    $q->where('nombre', 'like', "%{$search}%");
                }
            })
            ->orderBy('nombre')
            ->paginate(5); // <- límite de 5 categorías por página

        // para cada categoría cargar sus productos paginados (10 por página)
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

            // paginador por categoría; usa un nombre de página distinto para cada categoría
            $pageName = 'page_cat_' . $categoria->id;
            $categoria->setRelation('productos', $productosQuery->paginate(5, ['*'], $pageName));
        }

        return view('web.index', compact('categorias'));
    }

    public function show($id){
        // Obtener el producto por ID
        $producto = Producto::findOrFail($id);
        // Pasar el producto a la vista
        return view('web.item', compact('producto'));
    }
}
