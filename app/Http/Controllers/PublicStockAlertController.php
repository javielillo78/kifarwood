<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\StockAlert;
use Illuminate\Http\Request;

class PublicStockAlertController extends Controller
{
    public function store(Request $request, Producto $producto)
    {
        $user = $request->user();

        if ($producto->stock > 0) {
            return back()->with('toast_ok', 'Este producto ya tiene stock disponible.');
        }

        StockAlert::firstOrCreate(
            [
                'user_id'     => $user->id,
                'producto_id' => $producto->id,
            ],
            [
                'notified_at' => null,
            ]
        );

        return back()->with('toast_ok', 'Te avisaremos por correo cuando vuelva a haber stock.');
    }
}