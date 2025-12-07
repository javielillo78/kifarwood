@extends('public.layout')

@php use Illuminate\Support\Str; @endphp

@section('title', ($producto->nombre ?? __('site.products.title')).' · '.__('site.brand'))

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mb-3">
      <li class="breadcrumb-item"><a href="{{ route('public.index') }}">@lang('site.nav.home')</a></li>
      <li class="breadcrumb-item"><a href="{{ route('public.productos.index') }}">@lang('site.nav.products')</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre }}</li>
    </ol>
  </nav>
  <div class="row">
    <div class="col-lg-6 mb-3">
      <div class="glass p-2">
        <div id="prodShow-{{ $producto->id }}" class="carousel slide" data-ride="carousel" data-interval="4000">
          <div class="carousel-inner" style="aspect-ratio:4/3; background:#0f0f0f; border-radius:14px; overflow:hidden">
            @php $imgs = $producto->imagenes; @endphp
            @forelse($imgs as $k => $img)
              @php
                $src = $img->ruta ?? null;
                $url = $src
                    ? (Str::startsWith($src, ['http','//']) ? $src : asset(ltrim($src, '/')))
                    : asset('images/place.png');
              @endphp
              <div class="carousel-item {{ $k===0 ? 'active' : '' }}">
                <img src="{{ $url }}" class="d-block w-100" alt="{{ $producto->nombre }}" style="height:100%;object-fit:cover">
              </div>
            @empty
              <div class="carousel-item active">
                <img src="{{ asset('images/place.png') }}" class="d-block w-100" alt="{{ $producto->nombre }}" style="height:100%;object-fit:cover">
              </div>
            @endforelse
          </div>
          @if($imgs->count() > 1)
            <a class="carousel-control-prev" href="#prodShow-{{ $producto->id }}" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">@lang('site.common.previous')</span>
            </a>
            <a class="carousel-control-next" href="#prodShow-{{ $producto->id }}" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">@lang('site.common.next')</span>
            </a>
          @endif
        </div>
        @if($imgs->count() > 1)
          <div class="mt-2 d-flex flex-wrap" style="gap:8px">
            @foreach($imgs as $i => $img)
              @php
                $src = $img->ruta ?? null;
                $thumb = $src
                    ? (Str::startsWith($src, ['http','//']) ? $src : asset(ltrim($src, '/')))
                    : asset('images/place.png');
              @endphp
              <a href="#prodShow-{{ $producto->id }}" data-target="#prodShow-{{ $producto->id }}" data-slide-to="{{ $i }}" class="d-block" style="width:74px;height:60px;border-radius:10px;overflow:hidden;border:1px solid #2a292a">
                <img src="{{ $thumb }}" class="w-100 h-100" style="object-fit:cover" alt="thumb {{ $i+1 }}">
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
    <div class="col-lg-6 mb-3">
      <div class="glass p-4 h-100 position-relative">
        <a href="{{ route('public.productos.index') }}" class="btn btn-login btn-sm" style="position:absolute; top:12px; right:12px; z-index:2">
          <i class="fa fa-arrow-left mr-1"></i> @lang('site.common.back')
        </a>
        @php
          $stockDisp = (int)($producto->stock ?? 0);
          $enCarrito = 0;
          if (auth()->check()) {
              $pedidoTmp = \App\Models\Pedido::where('user_id', auth()->id())
                          ->where('estado','carrito')->first();
              if ($pedidoTmp) {
                  $detTmp = $pedidoTmp->detalles()->where('producto_id', $producto->id)->first();
                  $enCarrito = (int)($detTmp->cantidad ?? 0);
              }
          } else {
              $cookieCart = json_decode(request()->cookie('cart','[]'), true) ?: [];
              $enCarrito = (int)($cookieCart[$producto->id]['qty'] ?? 0);
          }
          $maxAdd = max(0, $stockDisp - $enCarrito);
        @endphp
        <div class="d-flex align-items-center mb-2" style="gap:10px">
          <span class="badge badge-light">{{ $producto->categoria->nombre ?? '—' }}</span>
          @if($stockDisp > 0)
            <span class="badge badge-success">@lang('site.products.in_stock')</span>
          @else
            <span class="badge badge-secondary">@lang('site.products.out_of_stock')</span>
          @endif
        </div>
        <h1 class="mb-2" style="font-weight:800">{{ $producto->nombre }}</h1>
        <div class="mb-3" style="font-size:1.4rem;font-weight:800;color:#ffdede">
          {{ number_format($producto->precio,2,',','.') }} €
        </div>
        @if($producto->descripcion)
          <div class="text-muted mb-4" style="line-height:1.6">{{ $producto->descripcion }}</div>
        @endif
        <div class="d-flex align-items-center" style="gap:10px">
          @if($stockDisp <= 0)
              @auth
                  @if(!empty($yaPideAvisoStock))
                      <button class="btn btn-secondary" disabled style="opacity:.8;cursor:not-allowed">
                          @lang('site.products.notify_stock')
                      </button>
                  @else
                      <form action="{{ route('public.productos.stock-alert', $producto) }}"
                            method="POST"
                            class="d-flex align-items-center" style="gap:10px">
                          @csrf
                          <button class="btn btn-outline-light">
                              <i class="fa fa-bell mr-1"></i> @lang('site.products.notify_me')
                          </button>
                      </form>
                  @endif
              @else
                  <a href="{{ route('login', ['redirect_to' => route('public.productos.show', $producto)]) }}"
                    class="btn btn-outline-light">
                      <i class="fa fa-bell mr-1"></i> @lang('site.products.login_notify')
                  </a>
              @endauth
          @elseif($maxAdd <= 0)
              <div>
                <small class="d-block mt-2 text-warning" style="font-size:.85rem;">
                  <i class="fa fa-circle-exclamation mr-1"></i>
                  @lang('site.products.max_reached')
                </small>
              </div>
          @else
              <form action="{{ route('public.cesta.add', $producto) }}" method="POST" class="d-flex align-items-center" style="gap:10px">
                @csrf
                <select name="qty" class="form-control" style="width:100px">
                  @for($i = 1; $i <= $maxAdd; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
                <button class="btn btn-primary">
                  <i class="fa fa-cart-plus mr-1"></i> @lang('site.products.add_to_cart')
                </button>
              </form>
          @endif
        </div>

        <div class="mt-3">
          @if($stockDisp > 0 && $maxAdd > 0 && $maxAdd <= 2)
              <div class="alert alert-warning mb-2" style="background:rgba(255,193,7,.10);border-color:rgba(255,193,7,.35);color:#ffe29a;">
                <i class="fa fa-exclamation-triangle mr-1"></i>
                @lang('site.products.limited_units', ['count' => $maxAdd])
              </div>
          @endif
        </div>
        <hr class="my-4" style="border-color:#2a292a">
        <small class="text-muted">@lang('site.products.ref'): PROD-{{ str_pad($producto->id, 5, '0', STR_PAD_LEFT) }}</small>
      </div>
    </div>
  </div>
@endsection