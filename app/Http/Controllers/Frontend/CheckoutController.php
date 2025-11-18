<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $pedido = Pedido::with('detalles.producto')
            ->where('user_id', $request->user()->id)
            ->where('estado','carrito')->first();

        if (!$pedido || $pedido->detalles->isEmpty()) {
            return redirect()->route('public.cesta.index')->with('cart_err','Tu cesta está vacía.');
        }

        $total = $pedido->detalles->sum('subtotal');
        $base  = round($total / 1.21, 2);
        $iva   = round($total - $base, 2);

        return view('public.cesta.checkout', compact('pedido','total','base','iva'));
    }

    public function place(Request $request)
    {
        $userId = $request->user()->id;

        $pedido = Pedido::with('detalles.producto')
            ->where('user_id',$userId)->where('estado','carrito')->lockForUpdate()->first();

        if (!$pedido || $pedido->detalles->isEmpty()) {
            return redirect()->route('public.cesta.index')->with('cart_err','Tu cesta está vacía.');
        }

        DB::transaction(function() use ($pedido) {
            foreach ($pedido->detalles as $d) {
                $p = $d->producto()->lockForUpdate()->first();
                $disp = (int)($p->stock ?? 0);
                if ($disp < $d->cantidad) {
                    throw new \RuntimeException("Stock insuficiente para {$p->nombre} (quedan {$disp}).");
                }
            }

            foreach ($pedido->detalles as $d) {
                $p = $d->producto()->lockForUpdate()->first();
                $p->stock = max(0, (int)$p->stock - (int)$d->cantidad);
                $p->save();
            }

            $pedido->estado = 'pagado'; 
            $pedido->fecha_pedido = now();
            $pedido->total = $pedido->detalles()->sum('subtotal');
            $pedido->save();

            Pago::create([
                'pedido_id'  => $pedido->id,
                'importe'    => $pedido->total,
                'fecha_pago' => now(),
            ]);
        });

        $total = $pedido->detalles->sum('subtotal');
        $base  = round($total / 1.21, 2);
        $iva   = round($total - $base, 2);

        $pdf = Pdf::loadView('pdf.factura', [
            'pedido' => $pedido,
            'total'  => $total,
            'base'   => $base,
            'iva'    => $iva,
            'fecha'  => Carbon::now(),
        ])->setPaper('a4');

        $path = "facturas/factura-{$pedido->id}.pdf";
        Storage::disk('local')->put($path, $pdf->output());

        try {
            $toClient = $pedido->usuario?->email;
            $toAdmin  = config('mail.contact_to', config('mail.from.address'));
            if ($toClient) {
                Mail::send('emails.pedido.confirmacion', ['pedido'=>$pedido,'total'=>$total,'base'=>$base,'iva'=>$iva], function($m) use ($toClient, $path) {
                    $m->to($toClient)->subject('Confirmación de pedido');
                    $m->attach(storage_path("app/{$path}"));
                });
            }
            if ($toAdmin) {
                Mail::send('emails.pedido.admin', ['pedido'=>$pedido,'total'=>$total,'base'=>$base,'iva'=>$iva], function($m) use ($toAdmin, $path) {
                    $m->to($toAdmin)->subject('Nuevo pedido recibido');
                    $m->attach(storage_path("app/{$path}"));
                });
            }
        } catch (\Throwable $e) {
        }

        return redirect()
            ->route('public.cesta.result', ['pedido'=>$pedido->id])
            ->with('order_ok', 'Compra realizada correctamente. Puedes descargar tu factura.');
    }

    public function download(Request $request, Pedido $pedido)
    {
        $this->authorize('view', $pedido);
        $path = "facturas/factura-{$pedido->id}.pdf";
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }
        return response()->download(storage_path("app/{$path}"));
    }
}