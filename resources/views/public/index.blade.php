@extends('public.layout')

@section('title','Inicio · Kifar Wood Studios')

@section('content')
  <div class="glass pulse p-4 p-md-5 mb-4">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <span class="badge badge-pill" style="background:rgba(188,34,37,.2); color:#ffdede; border:1px solid rgba(188,34,37,.45);">
          Hecho a mano · Madera de calidad
        </span>
        <h1 class="mt-3 mb-2" style="font-weight:800; letter-spacing:.2px;">Diseño y carpintería a medida</h1>
        <p class="text-muted mb-3" style="max-width:540px">
          Piezas únicas en madera: mesas, estanterías, decoración y proyectos personalizados para tu hogar o negocio.
        </p>
        <div>
          <a class="btn btn-primary mr-2" href="{{ route('public.productos.index') }}">
            Explorar productos
          </a>
          <a class="btn btn-login" href="{{ route('public.contacto.index') }}">
            Pedir presupuesto
          </a>
        </div>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="d-flex align-items-center justify-content-center" style="border-radius:18px; overflow:hidden; box-shadow:0 20px 40px rgba(188,34,37,.28);">
          <img src="{{ asset('images/logo_blanco.png') }}" alt="kifar-hero" class="img-fluid" style="max-width:220px; width:70%; height:auto;" onerror="this.style.display='none'">
          <div style="position:absolute; inset:auto 0 0 0; height:6px; background:linear-gradient(90deg, transparent, var(--brand), transparent); opacity:.7"></div>
        </div>
      </div>
    </div>
  </div>

  @php
    use Illuminate\Support\Str;
  @endphp

  @if($novedades->count())
    <div class="glass p-3 p-md-4 mb-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="mb-0" style="font-weight:800">Novedades</h2>
        <a href="{{ route('public.productos.index') }}" class="text-muted">Ver todos</a>
      </div>
      <div id="novedadesCarousel" class="carousel slide" data-ride="carousel" data-interval="3500" data-pause="false" data-touch="true" data-wrap="true">
        @if($novedades->count() > 1)
          <ol class="carousel-indicators mb-0">
            @foreach($novedades as $i => $p)
              <li data-target="#novedadesCarousel" data-slide-to="{{ $i }}" class="{{ $i===0 ? 'active' : '' }}"></li>
            @endforeach
          </ol>
        @endif
        <div class="carousel-inner">
          @foreach($novedades as $i => $p)
            @php
              $img = $p->imagenes->first();
              $src = $img->ruta ?? null;
              $url = $src ? (Str::startsWith($src, ['http','//']) ? $src : asset(ltrim($src,'/'))) : asset('images/place.png');
            @endphp
            <div class="carousel-item {{ $i===0 ? 'active' : '' }}">
              <div class="row align-items-center">
                <div class="col-lg-7 mb-3 mb-lg-0">
                  <a href="{{ route('public.productos.show', $p) }}" class="d-block" aria-label="{{ $p->nombre }}">
                    <div class="position-relative" style="aspect-ratio: 16/9; background:#0f0f0f; border-radius:16px; overflow:hidden;">
                      <img src="{{ $url }}" alt="{{ $p->nombre }}" class="w-100 h-100" style="object-fit:cover" loading="eager" decoding="async">
                    </div>
                  </a>
                </div>
                <div class="col-lg-5">
                  <span class="badge badge-light mb-2">Nuevo</span>
                  <h3 class="mb-2" style="font-weight:800">{{ $p->nombre }}</h3>
                  <div class="text-muted mb-2">{{ $p->categoria->nombre ?? '—' }}</div>
                  <div class="text-warning font-weight-bold mb-3">{{ number_format($p->precio,2,',','.') }} €</div>
                  <a class="btn btn-primary" href="{{ route('public.productos.show', $p) }}">Ver producto</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        @if($novedades->count() > 1)
          <a class="carousel-control-prev" href="#novedadesCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
          </a>
          <a class="carousel-control-next" href="#novedadesCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
          </a>
        @endif
      </div>
    </div>
  @endif

  @if(isset($masVendidos) && $masVendidos->count())
    <div class="glass p-3 p-md-4 mb-4" style="background:linear-gradient(145deg, rgba(12,12,12,.95), rgba(188,34,37,.22));">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
          <h2 class="mb-0" style="font-weight:800">Más vendidos</h2>
          <small class="text-muted">Lo que más se está llevando la gente últimamente</small>
        </div>
        <a href="{{ route('public.productos.index') }}" class="text-muted">Ver catálogo</a>
      </div>

      <div class="row">
        @foreach($masVendidos as $p)
          @php
            $img = $p->imagenes->first();
            $src = $img->ruta ?? null;
            $url = $src ? (Str::startsWith($src, ['http','//']) ? $src : asset(ltrim($src,'/'))) : asset('images/place.png');
            $vendidos = $p->total_vendido ?? ($p->detalles_sum_cantidad ?? 0);
          @endphp
          <div class="col-sm-6 col-lg-4 mb-3">
            <div class="glass h-100" style="border-radius:18px; overflow:hidden; background:rgba(0,0,0,.55); border:1px solid rgba(255,255,255,.04);">
              <div class="position-relative" style="height:170px; background:#0f0f0f; overflow:hidden;">
                <img src="{{ $url }}" alt="{{ $p->nombre }}" class="w-100 h-100" style="object-fit:cover;">
                <div style="position:absolute; top:8px; left:8px;">
                  <span class="badge badge-warning" style="font-size:.7rem;">
                    <i class="fa fa-crown mr-1"></i>Top ventas
                  </span>
                </div>
                @if(($p->stock ?? 0) <= 0)
                  <div style="position:absolute; bottom:8px; left:8px;">
                    <span class="badge badge-secondary" style="font-size:.7rem;">Sin stock</span>
                  </div>
                @elseif(($p->stock ?? 0) <= 3)
                  <div style="position:absolute; bottom:8px; left:8px;">
                    <span class="badge badge-danger" style="font-size:.7rem;">
                      Solo {{ $p->stock }} en stock
                    </span>
                  </div>
                @endif
              </div>
              <div class="p-3 d-flex flex-column">
                <div class="text-muted mb-1" style="font-size:.8rem;">
                  {{ $p->categoria->nombre ?? '—' }}
                </div>
                <div class="font-weight-bold mb-1" style="font-size:1rem;">
                  {{ Str::limit($p->nombre, 40) }}
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span style="color:#ffd27f; font-weight:700; font-size:1.05rem;">
                    {{ number_format($p->precio,2,',','.') }} €
                  </span>
                  <small class="text-muted" style="font-size:.8rem;">
                    Vendidos: {{ $vendidos }}
                  </small>
                </div>
                <a href="{{ route('public.productos.show', $p) }}" class="btn btn-sm btn-primary mt-auto">
                  Ver detalle
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
@endsection
