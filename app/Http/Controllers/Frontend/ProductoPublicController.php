<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\StockAlert;
use Illuminate\Support\Facades\Auth;

class ProductoPublicController extends Controller
{
    public function index() 
    {
        $productos = Producto::with(['imagenes','categoria'])
            ->orderBy('id','desc')
            ->paginate(9);

        return view('public.productos.index', compact('productos'));
    }

    public function show(Producto $producto)
    {
        $producto->load(['imagenes','categoria']);
        $yaPideAvisoStock = false;
        if (Auth::check()) {
            $yaPideAvisoStock = StockAlert::where('user_id', Auth::id())
                ->where('producto_id', $producto->id)
                ->whereNull('notified_at')
                ->exists();
        }
        return view('public.productos.show', compact('producto', 'yaPideAvisoStock'));
    }
}