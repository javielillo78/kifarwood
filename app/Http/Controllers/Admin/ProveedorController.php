<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('nombre')
            ->withCount('productos')
            ->paginate(15);

        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:150'],
        ]);

        Proveedor::create($data);

        return redirect()
            ->route('admin.proveedores.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    public function edit(Proveedor $proveedore)
    {
        $proveedor = $proveedore;
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedore)
    {
        $proveedor = $proveedore;

        $data = $request->validate([
            'nombre' => ['required','string','max:150'],
        ]);

        $proveedor->update($data);

        return redirect()
            ->route('admin.proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedore)
    {
        $proveedor = $proveedore;

        if ($proveedor->productos()->exists()) {
            return back()->with('error', 'No puedes borrar un proveedor que tiene productos asociados.');
        }

        $proveedor->delete();

        return back()->with('success', 'Proveedor eliminado correctamente.');
    }
}