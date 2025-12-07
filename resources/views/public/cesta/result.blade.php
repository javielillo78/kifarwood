@extends('public.layout')

@section('title','Pedido realizado · '.__('site.brand'))

@section('content')
  <h1 class="mb-3" style="font-weight:800">@lang('site.order.thanks')</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="glass p-4">
    <p class="mb-3">
      @lang('site.order.processed', ['id' => $pedido->id])
    </p>

    <a class="btn btn-primary" href="{{ route('public.cesta.factura', $pedido) }}">
      @lang('site.order.download_invoice')
    </a>
    <a class="btn btn-login ml-2" href="{{ route('public.index') }}">
      @lang('site.order.back_home')
    </a>
  </div>
@endsection