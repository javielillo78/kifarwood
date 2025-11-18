@extends('public.layout')

@section('title','Confirmar pedido · '.__('site.brand'))

@section('content')
  <h1 class="mb-3" style="font-weight:800">Confirmar pedido</h1>

  @if(!count($items))
    <div class="glass p-4">
      <p class="mb-0 text-muted">Tu cesta está vacía.</p>
    </div>
  @else
    <div class="glass p-3 p-md-4">
      <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
          <thead>
            <tr>
              <th>Producto</th>
              <th style="width:140px" class="text-right">Precio (IVA inc.)</th>
              <th style="width:120px" class="text-center">Cantidad</th>
              <th style="width:140px" class="text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $it)
              <tr>
                <td class="align-middle">
                  <div class="d-flex align-items-center" style="gap:12px">
                    <img src="{{ $it['img'] ?? asset('images/place.png') }}" alt=""
                         style="width:64px;height:48px;object-fit:cover;border-radius:8px">
                    <div class="font-weight-bold">{{ $it['name'] }}</div>
                  </div>
                </td>
                <td class="align-middle text-right">{{ number_format($it['price'],2,',','.') }} €</td>
                <td class="align-middle text-center">{{ $it['qty'] }}</td>
                <td class="align-middle text-right">{{ number_format($it['subtotal'],2,',','.') }} €</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-right">Base imponible</th>
              <th class="text-right">{{ number_format($base,2,',','.') }} €</th>
            </tr>
            <tr>
              <th colspan="3" class="text-right">IVA (21%)</th>
              <th class="text-right">{{ number_format($iva,2,',','.') }} €</th>
            </tr>
            <tr>
              <th colspan="3" class="text-right">Total (IVA inc.)</th>
              <th class="text-right">{{ number_format($total,2,',','.') }} €</th>
            </tr>
          </tfoot>
        </table>
      </div>

      @guest
        <div class="alert alert-warning mt-3 mb-0">
          Debes iniciar sesión para finalizar la compra.
        </div>
      @else
        <form action="{{ route('public.cesta.confirmar') }}" method="POST" class="mt-3">
          @csrf
          <button class="btn btn-primary">Pagar (simulación)</button>
        </form>
      @endguest
    </div>
  @endif
@endsection
