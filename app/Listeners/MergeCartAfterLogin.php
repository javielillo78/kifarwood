<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cookie;
use App\Models\Pedido;
use App\Models\Producto;

class MergeCartAfterLogin
{
    public function handle(Login $event): void
    {
        if (session()->pull('cart_merged', false)) {
            return;
        }
        session(['cart_merged' => true]);

        $user = $event->user;
        $lock = cache()->lock('merge-cart-'.$user->id, 5);
        if (! $lock->get()) {
            return;
        }

        try {
            $raw = request()->cookie('cart', '[]');
            $cookieCart = json_decode($raw, true) ?: [];

            if (!is_array($cookieCart) || empty($cookieCart)) {
                Cookie::queue(Cookie::forget('cart'));
                Cookie::queue('cart', '[]', 0);
                return;
            }

            $pedido = Pedido::firstOrCreate(
                ['user_id' => $user->id, 'estado' => 'carrito'],
                ['total' => 0]
            );

            $ajustadoPorStock = false;

            foreach ($cookieCart as $pid => $item) {
                $prodId   = (int) $pid;
                $producto = Producto::find($prodId);
                if (!$producto) {
                    continue;
                }

                $stock = (int) ($producto->stock ?? 0);
                if ($stock <= 0) {
                    $ajustadoPorStock = true;
                    continue;
                }

                $qtyCookie = max(1, (int) ($item['qty'] ?? 1));

                $detalle = $pedido->detalles()->firstOrNew(['producto_id' => $prodId]);

                $cantidadActual = (int) ($detalle->cantidad ?? 0);
                if ($cantidadActual > $stock) {
                    $cantidadActual = $stock;
                }

                $maxAdd = max(0, $stock - $cantidadActual);
                if ($maxAdd <= 0) {
                    $ajustadoPorStock = true;
                    $detalle->cantidad = $cantidadActual;
                    $detalle->subtotal = $cantidadActual * (float)($detalle->precio_unitario ?? $producto->precio);
                    $detalle->save();
                    continue;
                }

                $qtyToAdd = min($qtyCookie, $maxAdd);

                $precio = $detalle->exists
                    ? (float) $detalle->precio_unitario
                    : (float) ($item['price'] ?? $producto->precio ?? 0);

                if ($precio <= 0) {
                    $precio = (float) ($producto->precio ?? 0);
                }

                $detalle->precio_unitario = $precio;
                $detalle->cantidad        = $cantidadActual + $qtyToAdd;
                $detalle->subtotal        = $detalle->cantidad * $detalle->precio_unitario;
                $detalle->save();

                if ($qtyToAdd < $qtyCookie) {
                    $ajustadoPorStock = true;
                }
            }

            $pedido->total = $pedido->detalles()->sum('subtotal');
            $pedido->save();

            Cookie::queue(Cookie::forget('cart'));
            Cookie::queue('cart', '[]', 0);

            if ($ajustadoPorStock) {
                session()->flash('cart_err', 'Algunos productos se han ajustado por falta de stock disponible.');
            }

        } finally {
            optional($lock)->release();
        }
    }
}
