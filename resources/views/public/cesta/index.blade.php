@extends('public.layout')

@section('title', __('site.nav.cart').' · '.__('site.brand'))

@section('content')
  <h1 class="mb-3" style="font-weight:800">@lang('site.nav.cart')</h1>

  @if(!count($cart))
    <div class="glass p-4">
      <p class="mb-0 text-muted">@lang('site.cart.empty')</p>
    </div>
  @else
    <div class="glass p-3 p-md-4">
      <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
          <thead>
            <tr>
              <th>@lang('site.cart.product')</th>
              <th style="width:140px" class="text-right">@lang('site.cart.price')</th>
              <th style="width:180px" class="text-center">@lang('site.cart.quantity')</th>
              <th style="width:140px" class="text-right">@lang('site.cart.subtotal')</th>
              <th style="width:60px"></th>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach($cart as $item)
              @php
                $precio = (float)$item['price'];
                $cantidad = (int)$item['qty'];
                $subtotal = $precio * $cantidad;
                $total += $subtotal;
                $productoModel = \App\Models\Producto::find($item['id']);
                $stockMax = (int)($productoModel->stock ?? 0);
              @endphp
              <tr>
                <td class="align-middle">
                  <div class="d-flex align-items-center" style="gap:12px">
                    <img src="{{ $item['img'] ?? asset('images/place.png') }}" alt="{{ $item['name'] }}" style="width:64px;height:48px;object-fit:cover;border-radius:8px">
                    <div>
                      <div class="font-weight-bold">{{ $item['name'] }}</div>
                      <small class="text-muted">@lang('site.cart.ref') PROD-{{ str_pad($item['id'],5,'0',STR_PAD_LEFT) }}</small>
                    </div>
                  </div>
                </td>
                <td class="align-middle text-right">{{ number_format($precio,2,',','.') }} €</td>
                <td class="align-middle text-center">
                  <div class="d-inline-flex align-items-center" style="gap:8px">
                    @if($cantidad > 1)
                      <form action="{{ route('public.cesta.qty', $item['id']) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="qty" value="{{ $cantidad - 1 }}">
                        <button class="btn btn-login btn-sm" aria-label="@lang('site.cart.decrease')">&minus;</button>
                      </form>
                    @else
                      <form action="{{ route('public.cesta.remove', $item['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('@lang('site.cart.confirm_remove')')">
                        @csrf @method('DELETE')
                        <button class="btn btn-login btn-sm" aria-label="@lang('site.cart.remove')">&minus;</button>
                      </form>
                    @endif

                    <span class="px-2 font-weight-bold">{{ $cantidad }}</span>

                    @if($stockMax > 0 && $cantidad >= $stockMax)
                      <button class="btn btn-login btn-sm" type="button" disabled aria-label="@lang('site.cart.max_stock')" data-toggle="tooltip" data-placement="top" title="@lang('site.cart.max_stock')">+</button>
                    @else
                      <form action="{{ route('public.cesta.qty', $item['id']) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="qty" value="{{ $cantidad + 1 }}">
                        <button class="btn btn-login btn-sm" aria-label="@lang('site.cart.increase')">+</button>
                      </form>
                    @endif
                  </div>
                </td>
                <td class="align-middle text-right">{{ number_format($subtotal,2,',','.') }} €</td>
                <td class="align-middle text-right">
                  <form action="{{ route('public.cesta.remove', $item['id']) }}" method="POST" onsubmit="return confirm('@lang('site.cart.confirm_remove')')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-right">@lang('site.cart.total')</th>
              <th class="text-right">{{ number_format($total,2,',','.') }} €</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <form action="{{ route('public.cesta.clear') }}" method="POST" onsubmit="return confirm('@lang('site.cart.confirm_clear')')">
          @csrf @method('DELETE')
          <button class="btn btn-login">@lang('site.cart.clear_cart')</button>
        </form>
        <a href="{{ route('public.cesta.checkout') }}" class="btn btn-primary">@lang('site.cart.checkout')</a>
      </div>
    </div>
  @endif
@endsection
