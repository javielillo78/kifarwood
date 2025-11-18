@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo usuario
        </a>
    </div>
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Verificado</th>
                    <th>Rol</th>
                    <th class="text-right">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($usuarios as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if ($u->email_verified_at)
                                <span class="badge badge-success">Sí</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if ($u->rol === 'admin')
                                <span class="badge badge-danger">admin</span>
                            @else
                                <span class="badge badge-info">cliente</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.usuarios.edit', $u) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.usuarios.destroy', $u) }}"
                                method="POST"
                                class="form-eliminar"
                                data-nombre="{{ $u->nombre }}"
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
                    <tr><td colspan="6">No hay usuarios registrados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $usuarios->links() }}
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