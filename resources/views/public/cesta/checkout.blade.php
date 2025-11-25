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
          <div class="mt-2">
            <a href="{{ route('login', ['redirect_to' => route('public.cesta.checkout')]) }}" class="btn btn-primary btn-sm">
              Iniciar sesión y continuar
            </a>
          </div>
        </div>
      @else
        <div class="mt-3">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#facturacionModal">
            Pagar (simulación)
          </button>
        </div>
        <div class="modal fade" id="facturacionModal" tabindex="-1" role="dialog" aria-labelledby="facturacionModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bg-dark text-light">
              <div class="modal-header">
                <h5 class="modal-title" id="facturacionModalLabel">Datos de facturación</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{ route('public.cesta.confirmar') }}" method="POST">
                @csrf
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label>Nombre</label>
                      <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Correo electrónico</label>
                      <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="nif">NIF / DNI</label>
                      <input type="text" name="nif" id="nif" class="form-control @error('nif') is-invalid @enderror" value="{{ old('nif', auth()->user()->nif ?? '') }}">
                      @error('nif')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-6">
                      <label for="telefono">Teléfono (opcional)</label>
                      <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', auth()->user()->telefono ?? '') }}">
                      @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="direccion">Dirección (calle, número, piso…)</label>
                    <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', auth()->user()->direccion ?? '') }}">
                    @error('direccion')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="cp">Código postal</label>
                      <input type="text" name="cp" id="cp" class="form-control @error('cp') is-invalid @enderror" value="{{ old('cp', auth()->user()->cp ?? '') }}">
                      @error('cp')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-4">
                      <label for="ciudad">Población</label>
                      <input type="text" name="ciudad" id="ciudad" class="form-control @error('ciudad') is-invalid @enderror" value="{{ old('ciudad', auth()->user()->ciudad ?? '') }}">
                      @error('ciudad')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-md-4">
                      <label for="provincia">Provincia</label>
                      <input type="text" name="provincia" id="provincia" class="form-control @error('provincia') is-invalid @enderror" value="{{ old('provincia', auth()->user()->provincia ?? '') }}">
                      @error('provincia')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                  </button>
                  <button type="submit" class="btn btn-primary">
                    Confirmar y pagar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @endguest
    </div>
  @endif
@endsection
