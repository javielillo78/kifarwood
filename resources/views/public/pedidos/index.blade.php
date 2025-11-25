@extends('public.layout')

@section('title','Mis pedidos · '.__('site.brand'))

@section('content')
  <h1 class="mb-3" style="font-weight:800">Mis pedidos</h1>

  <div class="glass p-3 p-md-4 mb-3">
    <div class="alert mb-0" style="background:rgba(174, 177, 177, 0.08);border-color:rgba(197, 202, 203, 0.4);">
      Próximamente podrás solicitar devoluciones directamente desde esta sección.
      De momento, si necesitas gestionar una devolución, contacta con nosotros
      a través del formulario de contacto o por correo.
    </div>
  </div>
  @if($pedidos->count())
    <div class="glass p-3 p-md-4">
      <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
          <thead>
            <tr>
              <th>N.º pedido</th>
              <th>Fecha</th>
              <th class="text-center">Estado</th>
              <th class="text-right">Importe</th>
              <th style="width:160px" class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @foreach($pedidos as $pedido)
            @php
              $fecha = $pedido->fecha_pedido ?? $pedido->created_at;
              $config = $estadoConfig[$pedido->estado] ?? [
                  'label' => ucfirst($pedido->estado),
                  'class' => 'badge-secondary'
              ];
            @endphp
            <tr>
              <td>#{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</td>
              <td>
                {{ $fecha ? \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y H:i') : '—' }}
              </td>
              <td class="text-center">
                <span class="badge {{ $config['class'] }}">
                  {{ $config['label'] }}
                </span>
              </td>
              <td class="text-right">
                {{ number_format($pedido->total, 2, ',', '.') }} €
              </td>
              <td class="text-right">
                <a href="{{ route('public.cesta.factura', $pedido) }}"
                   class="btn btn-sm btn-outline-light"
                   target="_blank">
                  <i class="fas fa-file-pdf mr-1"></i> Factura
                </a>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      @if($pedidos->hasPages())
        <div class="mt-3">
          {{ $pedidos->links() }}
        </div>
      @endif
    </div>
  @else
    <div class="glass p-4">
      <p class="mb-0 text-muted">
        Todavía no tienes pedidos realizados.
      </p>
    </div>
  @endif
@endsection