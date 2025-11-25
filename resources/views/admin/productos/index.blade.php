@extends('adminlte::page')

@section('title', 'Productos')

@section('content_header')
    <h1>Productos</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo producto
        </a>
    </div>
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Precio (€)</th>
                    <th>Stock</th>
                    {{-- <th>Imágenes</th> --}}
                    <th class="text-right">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria?->nombre ?? '—' }}</td>
                        <td>{{ $producto->proveedor?->nombre ?? '—' }}</td>
                        <td>{{ number_format($producto->precio, 2, ',', '.') }} €</td>
                        <td>{{ $producto->stock }}</td>
                        {{-- <td>
                            @php $imgs = $producto->imagenes; @endphp
                            @if($imgs->count())
                                <div class="d-flex align-items-center" style="gap:6px">
                                    @foreach($imgs->take(3) as $img)
                                        <img src="{{ asset($img->ruta) }}" alt="img" style="width:40px;height:40px;border-radius:6px;object-fit:cover;border:1px solid #eee">
                                    @endforeach
                                    @if($imgs->count() > 3)
                                        <span class="badge badge-secondary">+{{ $imgs->count() - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">Sin imágenes</span>
                            @endif
                        </td> --}}
                        <td class="text-right">
                            <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" class="form-eliminar" data-nombre="{{ $producto->nombre }}" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No hay productos todavía.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $productos->links() }}
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