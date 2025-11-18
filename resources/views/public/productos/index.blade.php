@extends('public.layout')

@php use Illuminate\Support\Str; @endphp

@section('title','Productos · Kifar Wood Studios')

@section('content')
  <h1 class="mb-3">Productos</h1>
  @if($productos->count())
    <div class="row">
      @foreach($productos as $p)
        @php
          $stock    = (int)($p->stock ?? 0);
          $lowStock = $stock > 0 && $stock <= 2;
        @endphp
        <div class="col-sm-6 col-md-4 mb-3">
          <div class="glass h-100">
            <div class="position-relative"
                 style="aspect-ratio:4/3; background:#0f0f0f; border-top-left-radius:16px; border-top-right-radius:16px; overflow:hidden">
              <div id="prodCarousel-{{ $p->id }}" class="carousel slide h-100" data-ride="carousel" data-interval="3500">
                <div class="carousel-inner h-100">
                  @php $countImgs = $p->imagenes->count(); @endphp
                  @forelse($p->imagenes as $k => $img)
                    @php
                      $src = $img->ruta ?? null;
                      $url = $src
                          ? (Str::startsWith($src, ['http','//']) ? $src : asset(ltrim($src, '/')))
                          : asset('images/place.png');
                    @endphp
                    <div class="carousel-item {{ $k === 0 ? 'active' : '' }} h-100">
                      <img src="{{ $url }}" alt="{{ $p->nombre }}"
                           class="d-block w-100" style="height:100%;object-fit:cover">
                    </div>
                  @empty
                    <div class="carousel-item active h-100">
                      <img src="{{ asset('images/place.png') }}" alt="{{ $p->nombre }}" class="d-block w-100" style="height:100%;object-fit:cover">
                    </div>
                  @endforelse
                </div>
                @if($countImgs > 1)
                  <a class="carousel-control-prev" href="#prodCarousel-{{ $p->id }}" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                  </a>
                  <a class="carousel-control-next" href="#prodCarousel-{{ $p->id }}" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                  </a>
                  <ol class="carousel-indicators" style="bottom:6px;">
                    @for($i=0; $i<$countImgs; $i++)
                      <li data-target="#prodCarousel-{{ $p->id }}" data-slide-to="{{ $i }}"
                          class="{{ $i===0 ? 'active' : '' }}"></li>
                    @endfor
                  </ol>
                @endif
              </div>
            </div>
            <div class="p-3 position-relative">
              <div class="text-muted" style="font-size:.9rem">{{ $p->categoria->nombre ?? '—' }}</div>
              <div class="font-weight-bold">{{ $p->nombre }}</div>
              <div class="text-warning font-weight-bold">{{ number_format($p->precio,2,',','.') }} €</div>

              @if($stock <= 0)
                <div class="mt-1 small text-danger" style="font-weight:600;">No disponible en stock</div>
              @elseif($lowStock)
                <div class="mt-1 small text-danger" style="font-weight:600;">Solo quedan {{ $stock }} unidad(es)</div>
              @endif

              <a href="{{ route('public.productos.show', $p) }}" class="stretched-link" aria-label="{{ $p->nombre }}"></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-2">
      {{ $productos->links() }}
    </div>
  @else
    <p class="text-muted">Aún no hay productos.</p>
  @endif
@endsection