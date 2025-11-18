@extends('adminlte::page')

@section('title','Editar servicio')

@section('content_header')
  <h1>Editar servicio</h1>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">Revisa los campos.</div>
      @endif
      <form action="{{ route('admin.servicios.update',$servicio) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group">
          <label>Título</label>
          <input name="titulo" class="form-control" value="{{ old('titulo',$servicio->titulo) }}" required>
        </div>
        <div class="form-group">
          <label>Slug</label>
          <input name="slug" class="form-control" value="{{ old('slug',$servicio->slug) }}">
        </div>
        <div class="form-group">
          <label>Resumen</label>
          <input name="resumen" class="form-control" value="{{ old('resumen',$servicio->resumen) }}">
        </div>
        <div class="form-group">
          <label>Descripción</label>
          <textarea name="descripcion" class="form-control" rows="5">{{ old('descripcion',$servicio->descripcion) }}</textarea>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" {{ $servicio->activo ? 'checked' : '' }}>
          <label class="form-check-label" for="activo">Activo</label>
        </div>
        <div class="form-group">
          <label>Añadir imágenes</label>
          <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
          <small class="text-muted">Se añaden a las existentes.</small>
        </div>

        <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary mt-2">Cancelar</a>
        <button class="btn btn-primary mt-2">Actualizar</button>
      </form>
      @if($servicio->imagenes->count())
        <hr>
        <label>Imágenes actuales</label>
        <div class="d-flex flex-wrap" style="gap:12px">
          @foreach($servicio->imagenes as $img)
            <div class="border p-2" style="width:140px">
              <img src="{{ $img->url }}" alt="" style="width:100%;height:100px;object-fit:cover;border-radius:6px;">
              <form action="{{ route('admin.servicios.imagenes.destroy', $img) }}"
                    method="POST" class="form-eliminar mt-2" data-nombre="imagen #{{ $img->id }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger btn-block">
                  <i class="fas fa-trash-alt"></i> Borrar
                </button>
              </form>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection
@section('adminlte_js')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.querySelectorAll('.form-eliminar').forEach(function (f) {
      f.addEventListener('submit', function (e) {
        e.preventDefault();
        const nombre = f.dataset.nombre || 'esta imagen';
        Swal.fire({
          title: '¿Eliminar?',
          html: `Vas a eliminar <b>${nombre}</b>. Esta acción no se puede deshacer.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then(r => { if (r.isConfirmed) f.submit(); });
      });
    });
  </script>
@endsection