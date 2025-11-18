@extends('adminlte::page')

@section('title','Servicios')

@section('content_header')
  <h1>Servicios</h1>
@endsection

@section('content')
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="mb-3">
    <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Nuevo servicio
    </a>
  </div>
  <div class="card">
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Slug</th>
            <th>Activo</th>
            <th>Imágenes</th>
            <th class="text-right">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @forelse($servicios as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->titulo }}</td>
            <td class="text-muted">{{ $s->slug }}</td>
            <td>
              @if($s->activo)
                <span class="badge badge-success">Sí</span>
              @else
                <span class="badge badge-secondary">No</span>
              @endif
            </td>
            <td><span class="badge badge-info">{{ $s->imagenes_count }}</span></td>
            <td class="text-right">
              <a href="{{ route('admin.servicios.edit',$s) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('admin.servicios.destroy',$s) }}" method="POST"
                    class="d-inline form-eliminar" data-nombre="{{ $s->titulo }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">Sin registros.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $servicios->links() }}
    </div>
  </div>
@endsection
@section('adminlte_js')
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.querySelectorAll('.form-eliminar').forEach(f=>{
      f.addEventListener('submit', e=>{
        e.preventDefault();
        const nombre = f.dataset.nombre || 'este registro';
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