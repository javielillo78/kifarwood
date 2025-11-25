<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Producto;
use App\Models\Pedido;        
use App\Models\DetallePedido; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pago;

class CestaController extends Controller
{
    private string $cookieName = 'cart';
    private int $minutes = 60 * 24 * 365;

    private function readCookie(): array
    {
        $json = Cookie::get($this->cookieName, '[]');
        $cart = json_decode($json, true);
        return is_array($cart) ? $cart : [];
    }

    private function writeCookie(array $cart): void
    {
        Cookie::queue($this->cookieName, json_encode($cart), $this->minutes, null, null, false, false);
    }

    private function buildItemArrayFromModels($producto, int $qty): array
    {
        $img = $producto->imagenes()->orderBy('orden')->first();
        $imgUrl = $img?->ruta
            ? (str_starts_with($img->ruta,'http') ? $img->ruta : asset(ltrim($img->ruta,'/')))
            : asset('images/place.png');

        return [
            'id'    => $producto->id,
            'name'  => $producto->nombre,
            'price' => (float)$producto->precio,
            'qty'   => $qty,
            'img'   => $imgUrl,
        ];
    }

    private function arrayCount(array $cart): int
    {
        return array_sum(array_map(fn($i) => (int)($i['qty'] ?? 0), $cart));
    }

    private function arrayTotal(array $cart): float
    {
        return array_sum(array_map(fn($i) => (float)$i['price'] * (int)$i['qty'], $cart));
    }

    private function carritoDe(int $userId): Pedido
    {
        return Pedido::firstOrCreate(
            ['user_id' => $userId, 'estado' => 'carrito'],
            ['total' => 0]
        );
    }

    public function index(Request $request)
    {
        if ($request->user()) {
            $pedido = Pedido::with('detalles.producto.imagenes')
                ->where('user_id', $request->user()->id)
                ->where('estado', 'carrito')
                ->first();
            $cart = [];
            $total = 0;
            $count = 0;
            if ($pedido) {
                foreach ($pedido->detalles as $d) {
                    $item = $this->buildItemArrayFromModels($d->producto, (int)$d->cantidad);
                    $item['price'] = (float)$d->precio_unitario;      
                    $cart[$d->producto_id] = $item;
                    $total += $d->subtotal;
                    $count += (int)$d->cantidad;
                }
            }
            return view('public.cesta.index', compact('cart','total','count'));
        }
        $cart  = $this->readCookie();
        $total = $this->arrayTotal($cart);
        $count = $this->arrayCount($cart);
        return view('public.cesta.index', compact('cart','total','count'));
    }

    public function add(Request $request, Producto $producto)
    {
        $qtySolicitada = max(1, (int)$request->input('qty', 1));
        $stock = (int)($producto->stock ?? 0);

        if ($stock <= 0) {
            return back()->with('cart_err', 'No hay stock disponible de este producto.');
        }

        if ($request->user()) {
            $pedido  = $this->carritoDe($request->user()->id);

            $detalle = $pedido->detalles()->firstOrNew([
                'producto_id' => $producto->id,
            ]);

            $yaLleva = (int)($detalle->exists ? $detalle->cantidad : 0);
        } else {
            $cart    = $this->readCookie();
            $yaLleva = (int)($cart[$producto->id]['qty'] ?? 0);
            $detalle = null;
            $pedido  = null;
        }

        // Máximo permitido por stock total (no se reserva hasta pagar)
        $maxPosible = $stock;

        if ($yaLleva >= $maxPosible) {
            return back()->with('cart_err', 'No puedes añadir más unidades de este producto.');
        }

        $qtyAAgregar = min($qtySolicitada, $maxPosible - $yaLleva);

        if ($qtyAAgregar <= 0) {
            return back()->with('cart_err', 'No puedes añadir más unidades de este producto.');
        }

        if ($request->user()) {
            // Aquí $detalle ya tiene pedido_id porque viene de la relación firstOrNew
            $detalle->precio_unitario = (float)$producto->precio;
            $detalle->cantidad        = $yaLleva + $qtyAAgregar;
            $detalle->subtotal        = $detalle->cantidad * $detalle->precio_unitario;
            $detalle->save();

            $pedido->total = $pedido->detalles()->sum('subtotal');
            $pedido->save();
        } else {
            $cart = $this->readCookie();

            if (isset($cart[$producto->id])) {
                $cart[$producto->id]['qty'] += $qtyAAgregar;
            } else {
                $cart[$producto->id] = $this->buildItemArrayFromModels($producto, $qtyAAgregar);
            }

            $this->writeCookie($cart);
        }

        return back()->with('cart_ok', 'Producto añadido a la cesta.');
    }

   public function updateQty(Request $request, $id)
    {
        $producto = Producto::find((int)$id);

        if (!$producto) {
            return back()->with('cart_err', 'Producto no encontrado.');
        }

        $stock         = (int)($producto->stock ?? 0);
        $qtySolicitada = max(1, (int)$request->input('qty', 1));

        if ($stock <= 0) {
            if ($request->user()) {
                $pedido = $this->carritoDe($request->user()->id);
                $pedido->detalles()->where('producto_id', $producto->id)->delete();
                $pedido->total = $pedido->detalles()->sum('subtotal');
                $pedido->save();
            } else {
                $cart = $this->readCookie();
                unset($cart[$producto->id]);
                $this->writeCookie($cart);
            }

            return back()->with('cart_err', 'Este producto ya no está disponible.');
        }

        if ($qtySolicitada > $stock) {
            $qty     = $stock;
            $mensaje = 'No puedes añadir más unidades de este producto. Se ha ajustado a la cantidad máxima disponible.';
        } else {
            $qty     = $qtySolicitada;
            $mensaje = 'Cantidad actualizada.';
        }

        if ($request->user()) {
            $pedido  = $this->carritoDe($request->user()->id);
            $detalle = $pedido->detalles()->where('producto_id', $producto->id)->first();

            if ($detalle) {
                if ($qty <= 0) {
                    $detalle->delete();
                } else {
                    $detalle->cantidad = $qty;
                    $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
                    $detalle->save();
                }

                $pedido->total = $pedido->detalles()->sum('subtotal');
                $pedido->save();
            }
        } else {
            $cart = $this->readCookie();

            if (isset($cart[$producto->id])) {
                if ($qty <= 0) {
                    unset($cart[$producto->id]);
                } else {
                    $cart[$producto->id]['qty'] = $qty;
                }
                $this->writeCookie($cart);
            }
        }
        return back()->with('cart_ok', $mensaje);
    }

    public function remove(Request $request, $id)
    {
        if ($request->user()) {
            $pedido = $this->carritoDe($request->user()->id);
            $pedido->detalles()->where('producto_id', $id)->delete();
            $pedido->total = $pedido->detalles()->sum('subtotal');
            $pedido->save();
            return back()->with('cart_ok', 'Producto eliminado de la cesta.');
        }
        $cart = $this->readCookie();
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->writeCookie($cart);
        }
        return back()->with('cart_ok', 'Producto eliminado de la cesta.');
    }

    public function clear(Request $request)
    {
        if ($request->user()) {
            $pedido = $this->carritoDe($request->user()->id);
            $pedido->detalles()->delete();
            $pedido->total = 0;
            $pedido->save();
            return back()->with('cart_ok', 'Cesta vaciada.');
        }
        $this->writeCookie([]);
        return back()->with('cart_ok', 'Cesta vaciada.');
    }
    
    public function checkout(Request $request)
    {
        $user = $request->user();

        $pedido = Pedido::with('detalles.producto')
            ->where('user_id', $user->id)
            ->where('estado', 'carrito')
            ->first();

        if (!$pedido || $pedido->detalles->count() === 0) {
            return redirect()
                ->route('public.cesta.index')
                ->with('cart_err', 'Tu cesta está vacía.');
        }

        $items = [];
        foreach ($pedido->detalles as $d) {
            $items[] = [
                'name'     => $d->producto->nombre ?? '—',
                'img'      => $d->producto->imagenes()->orderBy('orden')->first()?->ruta,
                'price'    => (float) $d->precio_unitario,
                'qty'      => (int) $d->cantidad,
                'subtotal' => (float) $d->subtotal,
                'id'       => (int) $d->producto_id,
            ];
        }

        $total = array_sum(array_map(fn($i) => $i['subtotal'], $items));
        $base  = round($total / 1.21, 2);
        $iva   = round($total - $base, 2);

        return view('public.cesta.checkout', compact('items','total','base','iva'));
    }

    public function confirmar(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nif'       => ['required','string','max:20'],
            'direccion' => ['required','string','max:255'],
            'cp'        => ['required','string','max:10'],
            'ciudad'    => ['required','string','max:120'],
            'provincia' => ['required','string','max:120'],
            'telefono'  => ['nullable','string','max:20'],
        ]);

        $user->nif       = $data['nif'];
        $user->direccion = $data['direccion'];
        $user->cp        = $data['cp'];
        $user->ciudad    = $data['ciudad'];
        $user->provincia = $data['provincia'];
        $user->telefono  = $data['telefono'] ?? null;
        $user->save();

        $pedido = $this->carritoDe($user->id)->load('detalles.producto');
        if (!$pedido || $pedido->detalles()->count() === 0) {
            return redirect()->route('public.cesta.index')->with('cart_err', 'Tu cesta está vacía.');
        }

        foreach ($pedido->detalles as $d) {
            $stock = (int)($d->producto->stock ?? 0);
            if ($stock <= 0) {
                $d->cantidad = 0;
                $d->subtotal = 0;
                $d->save();
                continue;
            }
            if ($d->cantidad > $stock) {
                $d->cantidad = $stock;
                $d->subtotal = $d->cantidad * (float)$d->precio_unitario;
                $d->save();
            }
        }

        $pedido->total = $pedido->detalles()->sum('subtotal');
        if ($pedido->total <= 0) {
            return redirect()->route('public.cesta.index')->with('cart_err', 'No hay stock suficiente para completar el pedido.');
        }

        foreach ($pedido->detalles as $d) {
            if ($d->cantidad > 0) {
                $d->producto->decrement('stock', $d->cantidad);
            }
        }

        $pedido->estado = 'pagado';
        $pedido->fecha_pedido = now();
        $pedido->revisado_admin = false;
        $pedido->save();

        Pago::create([
            'pedido_id'  => $pedido->id,
            'importe'    => $pedido->total,
            'fecha_pago' => now(),
        ]);

        $base = round($pedido->total / 1.21, 2);
        $iva  = round($pedido->total - $base, 2);

        $empresa = $this->empresa();
        $cliente = $this->cliente($user);

        $pdf = Pdf::loadView('pdf.factura', [
            'pedido'     => $pedido,
            'ivaPercent' => 21,
        ]);

        $pdfBytes = $pdf->output();

        $adminEmail = config('mail.contact_to', config('mail.from.address'));
        Mail::to($user->email)->send(new \App\Mail\PedidoConfirmadoMail($pedido, $pdfBytes));
        Mail::to($adminEmail)->send(new \App\Mail\PedidoConfirmadoMail($pedido, $pdfBytes));

        return redirect()
            ->route('public.cesta.result', $pedido)
            ->with('success', 'Pedido realizado correctamente. Puedes descargar tu factura cuando quieras.');
    }

    public function factura(\App\Models\Pedido $pedido, \Illuminate\Http\Request $request)
    {
        $this->authorize('view', $pedido);

        $pedido->loadMissing('usuario', 'user');

        $userCliente = $pedido->usuario ?? $pedido->user ?? $request->user();
        $cliente = $this->cliente($userCliente);

        $base = round($pedido->total / 1.21, 2);
        $iva  = round($pedido->total - $base, 2);

        $empresa = $this->empresa();

        $pdf = Pdf::loadView('pdf.factura', [
            'pedido'     => $pedido,
            'ivaPercent' => 21,
        ]);

        return $pdf->download('factura-'.$pedido->id.'.pdf');
    }

    private function mergeCookieIntoCarritoIfNeeded(int $userId): void
    {
        $cookieCart = $this->readCookie();
        if (!is_array($cookieCart) || empty($cookieCart)) return;

        $pedido = Pedido::firstOrCreate(['user_id'=>$userId, 'estado'=>'carrito'], ['total'=>0]);
        $yaTiene = $pedido->detalles()->exists();
        if ($yaTiene) {
            Cookie::queue(Cookie::forget($this->cookieName));
            Cookie::queue($this->cookieName,'[]',0);
            return;
        }

        foreach ($cookieCart as $pid => $item) {
            $qty = max(1, (int)($item['qty'] ?? 1));
            $prodId = (int)$pid;
            $precio = (float)($item['price'] ?? 0);
            if ($precio <= 0) {
                $precio = (float)(Producto::find($prodId)?->precio ?? 0);
            }
            $detalle = $pedido->detalles()->firstOrNew(['producto_id'=>$prodId]);
            $detalle->precio_unitario = $precio;
            $detalle->cantidad = ($detalle->exists ? (int)$detalle->cantidad : 0) + $qty;
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
            $detalle->save();
        }
        $pedido->total = $pedido->detalles()->sum('subtotal');
        $pedido->save();

        Cookie::queue(Cookie::forget($this->cookieName));
        Cookie::queue($this->cookieName,'[]',0);
    }

    private function currentQtyInCart(Request $request, int $productoId): int
    {
        if ($request->user()) {
            $pedido = $this->carritoDe($request->user()->id);
            $det = $pedido->detalles()->where('producto_id', $productoId)->first();
            return (int)($det->cantidad ?? 0);
        }
        $cart = $this->readCookie();
        return (int)($cart[$productoId]['qty'] ?? 0);
    }

    private function empresa(): array
    {
        $idFiscal = config('site.company_nif', config('site.company_cif', '—'));

        $nombre   = config('site.company_name', config('app.name', ''));
        $dir      = config('site.company_address', '');
        $zip      = config('site.company_zip', '');
        $city     = config('site.company_city', '');
        $state    = config('site.company_state', '');
        $phone    = config('site.company_phone', '');
        $email    = config('mail.from.address', '');

        return [
            'nombre' => $nombre,
            'name'   => $nombre,
            'nif'    => $idFiscal,
            'cif'    => $idFiscal,
            'tax_id' => $idFiscal,
            'dir'        => $dir,
            'direccion'  => $dir,
            'address'    => $dir,
            'cp'         => $zip,
            'zip'        => $zip,
            'postalcode' => $zip,
            'poblacion'  => $city,
            'ciudad'     => $city,
            'city'       => $city,
            'provincia'  => $state,
            'state'      => $state,
            'region'     => $state,
            'telefono'   => $phone,
            'phone'      => $phone,
            'email'      => $email,
        ];
    }

    private function cliente(?\App\Models\User $user): array
    {
        if (!$user) {
            return [
                'nombre'    => 'Cliente',
                'name'      => 'Cliente',
                'email'     => '',
                'dir'       => '',
                'direccion' => '',
                'cp'        => '',
                'zip'       => '',
                'city'      => '',
                'poblacion' => '',
                'provincia' => '',
                'state'     => '',
                'phone'     => '',
                'telefono'  => '',
                'nif'       => '',
                'cif'       => '',
                'tax_id'    => '',
            ];
        }

        $nombre = $user->name ?? '';
        $email  = $user->email ?? '';
        $dir    = $user->direccion    ?? '';
        $zip    = $user->cp           ?? '';
        $city   = $user->ciudad       ?? '';
        $state  = $user->provincia    ?? '';
        $phone  = $user->telefono     ?? '';
        $nif    = $user->nif          ?? $user->cif ?? '';

        return [
            'nombre'    => $nombre,
            'name'      => $nombre,
            'email'     => $email,
            'dir'       => $dir,
            'direccion' => $dir,
            'address'   => $dir,
            'cp'        => $zip,
            'zip'       => $zip,
            'city'      => $city,
            'poblacion' => $city,
            'provincia' => $state,
            'state'     => $state,
            'telefono'  => $phone,
            'phone'     => $phone,
            'nif'       => $nif,
            'cif'       => $nif,
            'tax_id'    => $nif,
        ];
    }
}