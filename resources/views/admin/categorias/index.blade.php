@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <h1>Categorías</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva categoría
        </a>
    </div>
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th class="text-right">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->nombre }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categorias.destroy', $categoria) }}"
                                method="POST"
                                class="form-eliminar"
                                data-nombre="{{ $categoria->nombre }}"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No hay categorías todavía.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $categorias->links() }}
        </div>
    </div>
@endsection
@section('adminlte_js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.form-eliminar').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                const nombre = this.dataset.nombre || 'este registro';
                Swal.fire({
                    title: '¿Eliminar?',
                    html: `Vas a eliminar <b>${nombre}</b>. Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((r) => {
                    if (r.isConfirmed) this.submit();
                });
            });
        });
    });
    </script>
@endsection