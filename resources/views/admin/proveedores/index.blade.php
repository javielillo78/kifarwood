@extends('adminlte::page')

@section('title','Proveedores')

@section('content_header')
  <h1>Proveedores</h1>
@endsection

@section('content')
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Nuevo proveedor
    </a>
  </div>

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Productos asociados</th>
            <th class="text-right">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @forelse($proveedores as $p)
          <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->nombre }}</td>
            <td>
              <span class="badge badge-info">
                {{ $p->productos_count }}
              </span>
            </td>
            <td class="text-right">
              <a href="{{ route('admin.proveedores.edit',$p) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('admin.proveedores.destroy',$p) }}" method="POST"
                    class="d-inline"
                    onsubmit="return confirm('¿Eliminar este proveedor?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4">Sin registros.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $proveedores->links() }}
    </div>
  </div>
@endsection