<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with('categoria','imagenes')
            ->orderBy('id', 'asc')
            ->paginate(5);

        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'imagenes' => ['nullable','array'],
            'imagenes.*' => ['image','mimes:jpeg,jpg,png,webp','max:10240'],
        ]);
        $producto = Producto::create($request->only([
            'nombre',
            'categoria_id',
            'descripcion',
            'precio',
            'stock',
        ]));
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $idx => $file) {
                $path = $file->store("productos/{$producto->id}", 'public');   
                $publicUrl = Storage::url($path);                              
                Imagen::create([
                    'producto_id' => $producto->id,
                    'ruta'        => $publicUrl,   
                    'orden'       => $idx + 1,
                ]);
            }
        }
        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'        => ['required','string','max:150'],
            'categoria_id'  => ['required','exists:categorias,id'],
            'descripcion'   => ['nullable','string'],
            'precio'        => ['required','numeric','min:0'],
            'stock'         => ['required','integer','min:0'],
            'imagenes'      => ['nullable','array'],                   
            'imagenes.*'    => ['image','mimes:jpeg,jpg,png,webp','max:10240'], 
        ]);
        // Actualiza datos base
        $producto->update($request->only([
            'nombre','categoria_id','descripcion','precio','stock',
        ]));
        // Anexa nuevas imágenes si las hay
        if ($request->hasFile('imagenes')) {
            $nextOrder = ($producto->imagenes()->max('orden') ?? 0) + 1;
            foreach ($request->file('imagenes') as $i => $file) {
                $path = $file->store("productos/{$producto->id}", 'public');
                $publicUrl = Storage::url($path);
                Imagen::create([
                    'producto_id' => $producto->id,
                    'ruta'        => $publicUrl, 
                    'orden'       => $nextOrder + $i, 
                ]);
            }
        }
        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()
            ->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
