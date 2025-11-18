<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\ServicioImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::withCount('imagenes')
            ->orderByDesc('id')
            ->paginate(5);
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => ['required','string','max:150'],
            'slug'        => ['nullable','string','max:180','unique:servicios,slug'],
            'resumen'     => ['nullable','string','max:255'],
            'descripcion' => ['nullable','string'],
            'activo'      => ['sometimes','boolean'],
            'imagenes.*'  => ['nullable','image','max:4096'],
        ]);

        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->titulo);
        if (Servicio::where('slug',$slug)->exists()) {
            $slug .= '-'.Str::random(4);
        }
        $servicio = Servicio::create([
            'titulo'      => $request->titulo,
            'slug'        => $slug,
            'resumen'     => $request->resumen,
            'descripcion' => $request->descripcion,
            'activo'      => (bool)$request->boolean('activo'),
        ]);
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $i => $file) {
                $path = $file->store("servicios/{$servicio->id}", 'public');
                $url  = Storage::url($path);
                ServicioImagen::create([
                    'servicio_id' => $servicio->id,
                    'ruta'        => $url,
                    'orden'       => $i + 1,
                ]);
            }
        }
        return redirect()->route('admin.servicios.index')
            ->with('success','Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        $servicio->load('imagenes');
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'titulo'      => ['required','string','max:150'],
            'slug'        => ['nullable','string','max:180','unique:servicios,slug,'.$servicio->id],
            'resumen'     => ['nullable','string','max:255'],
            'descripcion' => ['nullable','string'],
            'activo'      => ['sometimes','boolean'],
            'imagenes.*'  => ['nullable','image','max:4096'],
        ]);
        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : $servicio->slug;
        if ($slug !== $servicio->slug && Servicio::where('slug',$slug)->exists()) {
            $slug .= '-'.Str::random(4);
        }
        $servicio->update([
            'titulo'      => $request->titulo,
            'slug'        => $slug,
            'resumen'     => $request->resumen,
            'descripcion' => $request->descripcion,
            'activo'      => (bool)$request->boolean('activo'),
        ]);
        if ($request->hasFile('imagenes')) {
            $next = ($servicio->imagenes()->max('orden') ?? 0) + 1;
            foreach ($request->file('imagenes') as $i => $file) {
                $path = $file->store("servicios/{$servicio->id}", 'public');
                $url  = Storage::url($path);
                ServicioImagen::create([
                    'servicio_id' => $servicio->id,
                    'ruta'        => $url,
                    'orden'       => $next + $i,
                ]);
            }
        }
        return redirect()->route('admin.servicios.index')
            ->with('success','Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        foreach ($servicio->imagenes as $img) {
            $this->deletePhysical($img->ruta);
            $img->delete();
        }
        Storage::disk('public')->deleteDirectory("servicios/{$servicio->id}");
        $servicio->delete();
        return redirect()->route('admin.servicios.index')
            ->with('success','Servicio eliminado correctamente.');
    }

    public function destroyImagen(ServicioImagen $imagen)
    {
        $this->deletePhysical($imagen->ruta);
        $imagen->delete();

        return back()->with('success','Ima  gen eliminada.');
    }

    private function deletePhysical(?string $publicUrl): void
    {
        if (!$publicUrl) return;
        $relative = ltrim(str_replace('/storage/','',$publicUrl),'/');
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}