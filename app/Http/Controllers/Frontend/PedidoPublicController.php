<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoPublicController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $pedidos = Pedido::with('detalles')
            ->where('user_id', $user->id)
            ->where('estado', '!=', 'carrito')
            ->orderByDesc('fecha_pedido')
            ->orderByDesc('id')
            ->paginate(10);

        $estadoConfig = [
            'carrito'    => ['label' => 'Borrador',   'class' => 'badge-secondary'],
            'pagado'     => ['label' => 'Pendiente',  'class' => 'badge-warning'],
            'pendiente'  => ['label' => 'Pendiente',  'class' => 'badge-warning'],
            'en_proceso' => ['label' => 'En proceso', 'class' => 'badge-info'],
            'enviado'    => ['label' => 'Enviado',    'class' => 'badge-primary'],
            'entregado'  => ['label' => 'Entregado',  'class' => 'badge-success'],
            'cancelado'  => ['label' => 'Cancelado',  'class' => 'badge-danger'],
        ];
        return view('public.pedidos.index', compact('pedidos', 'estadoConfig'));
    }
}