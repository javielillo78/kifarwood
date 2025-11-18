<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
    public function destroy(Imagen $imagen)
    {
        if ($imagen->ruta && str_starts_with($imagen->ruta, 'storage/')) {
            $diskPath = str_replace('storage/', 'public/', $imagen->ruta);
            Storage::delete($diskPath);
        }
        $productoId = $imagen->producto_id;
        $imagen->delete();
        return back()->with('success', 'Imagen eliminada correctamente.');
    }
}