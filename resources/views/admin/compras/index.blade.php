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
                                {{ $p->nombre }} (stock actual: {{ $p->stock ?? 0 }})
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
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial de compras/entradas de stock</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped mb-0">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th class="text-right">Unidades</th>
                    <th style="width:80px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($compras as $compra)
                    <tr>
                        <td>{{ optional($compra->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $compra->producto->nombre ?? '—' }}</td>
                        <td class="text-right">{{ $compra->unidades }}</td>
                        <td class="text-right">
                            <form action="{{ route('admin.compras.destroy', $compra) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar este registro de compra? Se ajustará el stock en negativo.');">
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
                        <td colspan="6" class="text-center text-muted">
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