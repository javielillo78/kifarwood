<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class ProductoPublicController extends Controller
{
    public function index() 
    {
        $productos = Producto::with(['imagenes','categoria'])
            ->orderBy('id','desc')
            ->paginate(9);

        return view('public.productos.index', compact('productos'));
    }
    public function show(\App\Models\Producto $producto)
    {
        $producto->load(['imagenes','categoria']);
        return view('public.productos.show', compact('producto'));
    }
}