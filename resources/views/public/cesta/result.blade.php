@extends('public.layout')

@section('title','Pedido realizado · '.__('site.brand'))

@section('content')
  <h1 class="mb-3" style="font-weight:800">¡Gracias por tu compra!</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="glass p-4">
    <p class="mb-3">Tu pedido #{{ $pedido->id }} se ha procesado correctamente.</p>
    <a class="btn btn-primary" href="{{ route('public.cesta.factura', $pedido) }}">
      Descargar factura (PDF)
    </a>
    <a class="btn btn-login ml-2" href="{{ route('public.index') }}">
      Volver al inicio
    </a>
  </div>
@endsection
