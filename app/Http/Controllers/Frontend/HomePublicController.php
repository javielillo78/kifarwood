<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class HomePublicController extends Controller
{
    public function index()
    {
        $novedades = Producto::with(['imagenes','categoria'])
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
        $masVendidos = Producto::select('productos.*')
            ->join('detalle_pedidos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->join('pedidos', 'pedidos.id', '=', 'detalle_pedidos.pedido_id')
            ->where('pedidos.estado', 'pagado')
            ->selectRaw('SUM(detalle_pedidos.cantidad) as total_vendido')
            ->groupBy('productos.id')
            ->orderByDesc('total_vendido')
            ->take(3)
            ->get();
        return view('public.index', compact('novedades','masVendidos'));
    }
}