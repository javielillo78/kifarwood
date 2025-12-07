@extends('public.layout')

@section('title', ($servicio->titulo ?? __('site.services.title')).' · '.__('site.brand'))

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mb-3">
      <li class="breadcrumb-item">
        <a href="{{ route('public.index') }}">@lang('site.nav.home')</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{ route('public.servicios.index') }}">@lang('site.nav.services')</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        {{ $servicio->titulo }}
      </li>
    </ol>
  </nav>

  <div class="row">
    <div class="col-lg-7 mb-3">
      <div class="glass p-2">
        <div id="servShow-{{ $servicio->id }}" class="carousel slide" data-ride="carousel" data-interval="4000">
          <div class="carousel-inner" style="aspect-ratio:4/3;background:#0f0f0f;border-radius:14px;overflow:hidden">
            @forelse($servicio->imagenes as $k => $img)
              <div class="carousel-item {{ $k===0 ? 'active' : '' }}">
                <img src="{{ $img->url }}" class="d-block w-100" style="height:100%;object-fit:cover" alt="{{ $servicio->titulo }}">
              </div>
            @empty
              <div class="carousel-item active">
                <img src="{{ asset('images/place.png') }}" class="d-block w-100" style="height:100%;object-fit:cover" alt="{{ $servicio->titulo }}">
              </div>
            @endforelse
          </div>

          @if($servicio->imagenes->count() > 1)
            <a class="carousel-control-prev" href="#servShow-{{ $servicio->id }}" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#servShow-{{ $servicio->id }}" role="button" data-slide="next">
              <span class="carousel-control-next-icon"></span>
            </a>
          @endif
        </div>

        @if($servicio->imagenes->count() > 1)
          <div class="mt-2 d-flex flex-wrap" style="gap:6px;">
            @foreach($servicio->imagenes as $i => $img)
              <a href="#servShow-{{ $servicio->id }}" data-target="#servShow-{{ $servicio->id }}" data-slide-to="{{ $i }}" class="d-block" style="width:70px;height:54px;border-radius:8px;overflow:hidden;border:1px solid #2a292a;">
                <img src="{{ $img->url }}" alt="thumb {{ $i+1 }}" class="w-100 h-100" style="object-fit:cover;">
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <div class="col-lg-5 mb-3">
      <div class="glass p-4 h-100 d-flex flex-column">
        <h1 class="mb-2" style="font-weight:800">{{ $servicio->titulo }}</h1>

        @if($servicio->descripcion)
          <div class="text-muted mb-3" style="line-height:1.6">
            {!! nl2br(e($servicio->descripcion)) !!}
          </div>
        @endif

        <div class="mb-3">
          <h5 class="mb-2" style="font-weight:700;font-size:.95rem;">@lang('site.services.how_we_work')</h5>
          <ul class="list-unstyled mb-0" style="font-size:.9rem;">
            <li class="mb-1">
              <i class="fa fa-pen-ruler mr-2 text-warning"></i> @lang('site.services.listen_define')
            </li>
            <li class="mb-1">
              <i class="fa fa-cube mr-2 text-warning"></i> @lang('site.services.design_fabricate')
            </li>
            <li class="mb-1">
              <i class="fa fa-truck mr-2 text-warning"></i> @lang('site.services.install_final')
            </li>
          </ul>
        </div>

        <div class="mt-auto pt-3 d-flex flex-wrap" style="gap:10px;">
          <a href="{{ route('public.contacto.index') }}" class="btn btn-primary">
            @lang('site.services.request_quote')
          </a>
          <a href="{{ route('public.servicios.index') }}" class="btn btn-login">
            @lang('site.common.back_to_services')
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
