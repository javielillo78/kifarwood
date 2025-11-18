<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::orderBy('id', 'asc')->paginate(5);
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
        ]);
        Categoria::create([
            'nombre' => $request->nombre,
        ]);
        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
        ]);
        $categoria->update([
            'nombre' => $request->nombre,
        ]);
        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}