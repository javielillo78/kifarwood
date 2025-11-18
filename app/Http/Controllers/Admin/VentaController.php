<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $from   = $request->input('from');
        $to     = $request->input('to');

        $baseQuery = Pedido::with('usuario')
            ->where('estado', 'pagado');

        if ($userId) {
            $baseQuery->where('user_id', $userId);
        }

        if ($from) {
            $baseQuery->whereDate('fecha_pedido', '>=', $from);
        }

        if ($to) {
            $baseQuery->whereDate('fecha_pedido', '<=', $to);
        }

        $nuevosPedidos = (clone $baseQuery)
            ->where('revisado_admin', false)
            ->count();

        $pedidos = $baseQuery
            ->orderByDesc('fecha_pedido')
            ->paginate(20);

        if ($nuevosPedidos > 0) {
            Pedido::where('estado', 'pagado')
                ->where('revisado_admin', false)
                ->update(['revisado_admin' => true]);
        }

        $usuarios = User::orderBy('name')->get();

        $importeTotalUsuario = null;
        if ($userId) {
            $importeTotalUsuario = Pedido::where('estado', 'pagado')
                ->where('user_id', $userId)
                ->when($from, fn($q) => $q->whereDate('fecha_pedido', '>=', $from))
                ->when($to,   fn($q) => $q->whereDate('fecha_pedido', '<=', $to))
                ->sum('total');
        }

        return view('admin.ventas.index', compact(
            'pedidos',
            'usuarios',
            'importeTotalUsuario',
            'userId',
            'from',
            'to',
            'nuevosPedidos'
        ));
    }

    public function destroy(Pedido $pedido)
    {
        if ($pedido->estado !== 'pagado') {
            return back()->with('ventas_err', 'Solo se pueden borrar pedidos pagados.');
        }

        DB::transaction(function () use ($pedido) {
            if (method_exists($pedido, 'pagos')) {
                $pedido->pagos()->delete();
            }

            $pedido->detalles()->delete();
            $pedido->delete();
        });

        return back()->with('ventas_ok', 'Pedido eliminado correctamente.');
    }
}