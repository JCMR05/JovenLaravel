<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('producto-list'); 
        $texto=$request->input('texto');
        $registros=Categoria::where('nombre', 'like',"%{$texto}%")
                    ->orWhere('codigo', 'like',"%{$texto}%")
                    ->orderBy('id', 'desc')
                    ->paginate(10);
        return view('categoria.index', compact('registros','texto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('producto-create'); 
        return view('categoria.action');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('producto-create'); 
        $registro = new Categoria();
        $registro->codigo=$request->input('codigo');
        $registro->nombre=$request->input('nombre');
        $registro->descripcion=$request->input('descripcion');

        $registro->save();
        return redirect()->route('categorias.index')->with('mensaje', 'Registro '.$registro->nombre. '  agregado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('producto-edit'); 
        $registro=Categoria::findOrFail($id);
        return view('categoria.action', compact('registro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, $id)
    {
        $this->authorize('producto-edit'); 
        $registro=Categoria::findOrFail($id);
        $registro->codigo=$request->input('codigo');
        $registro->nombre=$request->input('nombre');
        $registro->descripcion=$request->input('descripcion');

        $registro->save();

        return redirect()->route('categorias.index')->with('mensaje', 'Registro '.$registro->nombre. '  actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('producto-delete');
        $registro=Categoria::findOrFail($id);
        $registro->delete();
        return redirect()->route('productos.index')->with('mensaje', $registro->nombre. ' eliminado correctamente.');
    }
}
