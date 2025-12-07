@extends('public.layout')

@section('title', __('site.nav.contact').' · '.__('site.brand'))

@section('content')
  <h1 class="mb-3" style="font-weight:800">@lang('site.contact.title')</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row">
    <div class="col-lg-6 mb-3">
      <div class="glass p-3 p-md-4 h-100">
        <h3 class="mb-3" style="font-weight:700">@lang('site.contact.send_message')</h3>
        <form action="{{ route('public.contacto.send') }}" method="POST" novalidate>
          @csrf
          @guest
            <div class="form-group">
              <label>@lang('site.contact.your_name') *</label>
              <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
              @error('nombre') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
              <label>@lang('site.contact.your_email') *</label>
              <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
              @error('email') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>
          @else
            <input type="hidden" name="nombre" value="{{ auth()->user()->name }}">
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
          @endguest
          <div class="form-group">
            <label>@lang('site.contact.phone_optional')</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            @error('telefono') <small class="text-danger d-block">{{ $message }}</small> @enderror
          </div>
          <div class="form-group">
            <label>@lang('site.contact.subject') *</label>
            <input type="text" name="asunto" class="form-control" value="{{ old('asunto') }}" required>
            @error('asunto') <small class="text-danger d-block">{{ $message }}</small> @enderror
          </div>
          <div class="form-group">
            <label>@lang('site.contact.message') *</label>
            <textarea name="mensaje" rows="6" class="form-control" required>{{ old('mensaje') }}</textarea>
            @error('mensaje') <small class="text-danger d-block">{{ $message }}</small> @enderror
          </div>
          <button class="btn btn-primary">@lang('site.contact.send_button')</button>
        </form>
      </div>
    </div>

    <div class="col-lg-6 mb-3">
      <div class="glass p-3 p-md-4 h-100">
        <h3 class="mb-3" style="font-weight:700">@lang('site.contact.where_we_are')</h3>
        <div class="mb-3" style="border-radius:12px; overflow:hidden">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1578.0881848797721!2d-5.060278489554515!3d37.71553780637059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd12d34170e83ae3%3A0xab7329e74ed2aad1!2sKifar%20Wood%20Studios!5e0!3m2!1ses!2ses!4v1762759973724!5m2!1ses!2ses" width="100%" height="300" style="border:0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="text-muted">
          <p class="mb-1"><i class="fa fa-location-dot mr-2"></i> @lang('site.contact.address')</p>
          <p class="mb-1"><i class="fa fa-envelope mr-2"></i> {{ config('mail.contact_to', config('mail.from.address')) }}</p>
          <p class="mb-1"><i class="fa fa-clock mr-2"></i> @lang('site.contact.hours_mon_thu')</p>
          <p class="mb-1"><i class="fa fa-clock mr-2"></i> @lang('site.contact.hours_fri')</p>
          <p class="mb-0"><i class="fa fa-clock mr-2"></i> @lang('site.contact.hours_sat')</p>
        </div>
      </div>
    </div>
  </div>
@endsection
