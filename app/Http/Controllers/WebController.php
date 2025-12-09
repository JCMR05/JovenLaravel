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
        
        // Obtener productos relacionados (misma categoría)
        $productosRelacionados = collect();
        
        if ($producto->categorias->count() > 0) {
            $categoriaIds = $producto->categorias->pluck('id');
            $productosRelacionados = Producto::with('categorias')
                ->whereHas('categorias', function($q) use ($categoriaIds) {
                    $q->whereIn('categorias.id', $categoriaIds);
                })
                ->where('id', '!=', $producto->id)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }
        
        // Si no hay suficientes relacionados, completar con otros productos
        if ($productosRelacionados->count() < 4) {
            $idsExcluir = $productosRelacionados->pluck('id')->push($producto->id);
            $faltantes = 4 - $productosRelacionados->count();
            
            $productosAdicionales = Producto::with('categorias')
                ->whereNotIn('id', $idsExcluir)
                ->inRandomOrder()
                ->take($faltantes)
                ->get();
                
            $productosRelacionados = $productosRelacionados->merge($productosAdicionales);
        }
        
        return view('web.item', compact('producto', 'productosRelacionados'));
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

    public function perfil()
    {
        $user = auth()->user();
        
        // Contar pedidos del usuario
        $pedidos = \App\Models\Pedido::where('user_id', $user->id)->count();
        
        // Contar favoritos del usuario
        $favoritos = \App\Models\Favorito::where('user_id', $user->id)->count();
        
        // Obtener puntos del usuario
        $puntos = $user->puntos ?? 0;
        
        return view('web.perfil', compact('user', 'pedidos', 'favoritos', 'puntos'));
    }

    public function perfilUpdate(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->route('perfil')->with('status', 'Perfil actualizado correctamente');
    }

    public function misPedidos()
    {
        $user = auth()->user();
        $pedidos = $user->pedidos()
                        ->with('detalles.producto')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        $totalPedidos = $user->pedidos()->count();

        return view('web.mis-pedidos', compact('pedidos', 'totalPedidos'));
    }
}