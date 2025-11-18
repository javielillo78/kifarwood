<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::orderBy('nombre')->get();

        $compras = Compra::with(['producto','usuario'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.compras.index', compact('productos','compras'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'producto_id' => ['required','exists:productos,id'],
            'unidades'    => ['required','integer','min:1'],
        ]);

        DB::transaction(function () use ($data) {
            $producto = Producto::lockForUpdate()->findOrFail($data['producto_id']);

            $producto->increment('stock', $data['unidades']);

            Compra::create([
                'producto_id' => $producto->id,
                'user_id'     => Auth::id(),
                'unidades'    => $data['unidades'],
            ]);
        });

        return back()->with('compras_ok', 'Entrada de stock registrada correctamente.');
    }

    public function destroy(Compra $compra)
    {
        DB::transaction(function () use ($compra) {
            $producto = $compra->producto()->lockForUpdate()->first();
            if ($producto) {
                $nuevoStock = max(0, (int)$producto->stock - (int)$compra->unidades);
                $producto->update(['stock' => $nuevoStock]);
            }
            $compra->delete();
        });

        return back()->with('compras_ok', 'Registro de compra eliminado y stock ajustado.');
    }
}