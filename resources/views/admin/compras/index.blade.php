@extends('adminlte::page')

@section('title', 'Gestión de compras')

@section('content_header')
    <h1>Gestión de compras</h1>
@stop

@section('content')
    @if(session('compras_ok'))
        <div class="alert alert-success">
            {{ session('compras_ok') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            Hay errores en el formulario. Revisa los campos.
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Registrar entrada de stock</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.compras.store') }}" class="form-inline">
                @csrf

                <div class="form-group mr-3 mb-2">
                    <label for="producto_id" class="mr-2">Producto</label>
                    <select name="producto_id" id="producto_id"
                            class="form-control @error('producto_id') is-invalid @enderror">
                        <option value="">-- Selecciona producto --</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->id }}" {{ old('producto_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }}
                                @if($p->proveedor)
                                    — {{ $p->proveedor->nombre }}
                                @endif
                                (stock actual: {{ $p->stock ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                    @error('producto_id')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="unidades" class="mr-2">Unidades</label>
                    <input type="number" name="unidades" id="unidades" min="1"
                           value="{{ old('unidades', 1) }}"
                           class="form-control @error('unidades') is-invalid @enderror">
                    @error('unidades')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-plus"></i> Añadir a stock
                </button>
                <a href="{{ route('admin.productos.create') }}" class="btn btn-warning mb-2 ml-2">
                    <i class="fas fa-box-open"></i> Añadir producto
                </a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial de compras / entradas de stock</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped mb-0">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Proveedor</th>
                    <th class="text-right">Precio unidad</th>
                    <th class="text-center" style="width:160px">Unidades</th>
                    <th style="width:80px;" class="text-right">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($compras as $compra)
                    @php
                        $producto   = $compra->producto;
                        $precioU    = $producto ? (float)($producto->precio ?? 0) : 0;
                        $totalLinea = $precioU * (int)$compra->unidades;
                    @endphp
                    <tr>
                        <td>{{ optional($compra->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $producto->nombre ?? '—' }}</td>
                        <td>{{ $producto->proveedor->nombre ?? '—' }}</td>
                        <td class="text-right">
                            {{ number_format($precioU, 2, ',', '.') }} €
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center">

                                @if($compra->unidades > 1)
                                    <form action="{{ route('admin.compras.update', $compra) }}"
                                          method="POST" class="mr-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="unidades"
                                               value="{{ $compra->unidades - 1 }}">
                                        <button class="btn btn-xs btn-outline-secondary"
                                                title="Restar 1">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </form>
                                @endif

                                <span class="badge badge-light px-2 py-1">
                                    {{ $compra->unidades }}
                                </span>

                                <form action="{{ route('admin.compras.update', $compra) }}"
                                      method="POST" class="ml-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="unidades" value="{{ $compra->unidades + 1 }}">
                                    <button class="btn btn-xs btn-outline-secondary" title="Sumar 1">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.compras.destroy', $compra) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro de compra? Se ajustará el stock en negativo.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No hay compras registradas todavía.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($compras->hasPages())
            <div class="card-footer">
                {{ $compras->links() }}
            </div>
        @endif
    </div>
@stop
