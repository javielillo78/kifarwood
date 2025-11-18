@extends('adminlte::page')

@section('title','Nuevo servicio')

@section('content_header')
  <h1>Nuevo servicio</h1>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">Revisa los campos.</div>
      @endif
      <form action="{{ route('admin.servicios.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label>Título</label>
          <input name="titulo" class="form-control" value="{{ old('titulo') }}" required>
          @error('titulo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label>Slug (opcional)</label>
          <input name="slug" class="form-control" value="{{ old('slug') }}">
          @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
          <small class="text-muted">Si lo dejas vacío se generará a partir del título.</small>
        </div>
        <div class="form-group">
          <label>Resumen</label>
          <input name="resumen" class="form-control" value="{{ old('resumen') }}">
          @error('resumen') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label>Descripción</label>
          <textarea name="descripcion" class="form-control" rows="5">{{ old('descripcion') }}</textarea>
          @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" checked>
          <label class="form-check-label" for="activo">Activo</label>
        </div>
        <div class="form-group">
          <label>Imágenes (múltiples)</label>
          <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
          @error('imagenes.*') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">Cancelar</a>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
@endsection