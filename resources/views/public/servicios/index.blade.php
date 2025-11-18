@extends('public.layout')

@section('title','Servicios · '.__('site.brand'))

@section('content')
  <h1 class="mb-2" style="font-weight:800">Servicios</h1>
  <p class="text-muted mb-4" style="max-width:640px">
    Proyectos de carpintería a medida para hogares y negocios: desde mobiliario personalizado
    hasta soluciones integrales de diseño y montaje.
  </p>

  @php
    use Illuminate\Support\Str;
    $destacado = $servicios->first();
    $otros     = $servicios->slice(1);
  @endphp

  {{-- Servicio destacado --}}
  @if($destacado)
    @php
      $imgDest = $destacado->imagenes->first();
      $srcDest = $imgDest?->url ?? asset('images/place.png');
    @endphp
    <div class="glass p-3 p-md-4 mb-4">
      <div class="row align-items-stretch">
        <div class="col-lg-6 mb-3 mb-lg-0">
          <div class="h-100 position-relative"
               style="border-radius:16px; overflow:hidden; background:#0f0f0f;">
            <div style="
                position:absolute;
                inset:0;
                background-image:url('{{ $srcDest }}');
                background-size:cover;
                background-position:center;
                filter:brightness(0.8);
            "></div>
            <div style="
                position:absolute;
                inset:auto 0 0 0;
                height:40%;
                background:linear-gradient(180deg,transparent,rgba(0,0,0,.85));
            "></div>
            <div class="position-relative p-3 p-md-4 d-flex flex-column justify-content-end h-100">
              <h2 class="mb-1" style="font-weight:800">{{ $destacado->titulo }}</h2>
              @if($destacado->resumen)
                <p class="mb-2" style="color:#f8f8f8;">
                  {{ Str::limit($destacado->resumen, 120) }}
                </p>
              @endif
              <a href="{{ route('public.servicios.show', $destacado->slug) }}"
                 class="btn btn-primary btn-sm align-self-start mt-1">
                Ver detalles
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-6 d-flex flex-column">
          <div class="mb-3">
            <h3 class="mb-1" style="font-weight:700">Carpintería a tu medida</h3>
            <p class="text-muted mb-2" style="font-size:.95rem; line-height:1.5;">
              Te acompañamos en todo el proceso: idea, diseño, fabricación e instalación.
              Trabajamos cada proyecto de forma personalizada para adaptarnos a tu espacio y presupuesto.
            </p>
          </div>
          <ul class="list-unstyled mb-3" style="font-size:.95rem;">
            <li class="mb-1">
              <i class="fa fa-check text-success mr-2"></i>
              Diseño y asesoramiento personalizado.
            </li>
            <li class="mb-1">
              <i class="fa fa-check text-success mr-2"></i>
              Instalación profesional y acabados cuidados.
            </li>
            <li class="mb-1">
              <i class="fa fa-check text-success mr-2"></i>
              Madera de calidad y fabricación propia.
            </li>
          </ul>
          <div class="mt-auto">
            <a href="{{ route('public.contacto.index') }}" class="btn btn-login mr-2 mb-2">
              Solicitar presupuesto
            </a>
            <a href="{{ route('public.productos.index') }}" class="btn btn-outline-light btn-sm mb-2">
              Ver productos terminados
            </a>
          </div>
        </div>
      </div>
    </div>
  @endif

  {{-- Resto de servicios --}}
  @if($otros->count())
    <div class="mb-2 d-flex align-items-center justify-content-between">
      <h2 class="mb-0" style="font-weight:800; font-size:1.2rem;">Otros servicios</h2>
    </div>

    <div class="row">
      @foreach($otros as $s)
        @php
          $img  = $s->imagenes->first();
          $src  = $img?->url ?? asset('images/place.png');
          $text = $s->resumen ?: Str::limit($s->descripcion, 90);
        @endphp
        <div class="col-md-6 col-lg-4 mb-3">
          <div class="glass h-100 d-flex flex-column">
            <div class="position-relative"
                 style="height:170px; border-top-left-radius:16px; border-top-right-radius:16px; overflow:hidden;">
              <img src="{{ $src }}" alt="{{ $s->titulo }}"
                   class="w-100 h-100" style="object-fit:cover;">
              <span class="badge badge-dark"
                    style="position:absolute; top:8px; left:8px; background:rgba(0,0,0,.65);">
                Servicio
              </span>
            </div>
            <div class="p-3 d-flex flex-column flex-grow-1 position-relative">
              <h3 class="mb-1" style="font-size:1.05rem; font-weight:700;">
                {{ $s->titulo }}
              </h3>
              @if($text)
                <p class="text-muted mb-3" style="font-size:.9rem;">
                  {{ Str::limit($text, 110) }}
                </p>
              @endif
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size:.8rem;">Proyecto a medida</span>
                <a href="{{ route('public.servicios.show', $s->slug) }}"
                   class="btn btn-sm btn-primary">
                  Ver más
                </a>
              </div>
              <a href="{{ route('public.servicios.show', $s->slug) }}"
                 class="stretched-link" aria-label="{{ $s->titulo }}"></a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    @if(!$destacado)
      <p class="text-muted">Aún no hay servicios.</p>
    @endif
  @endif
@endsection
