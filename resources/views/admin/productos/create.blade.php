@extends('adminlte::page')

@section('title', 'Nuevo producto')

@section('content_header')
    <h1>Nuevo producto</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Revisa los campos marcados.</p>
                </div>
            @endif
            <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" class="form-control" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                <label>Proveedor</label>
                <select name="proveedor_id" class="form-control">
                    <option value="">-- Sin proveedor --</option>
                    @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>
                            {{ $prov->nombre }}
                        </option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Precio (€)</label>
                    <input type="number" name="precio" class="form-control" step="0.01" min="0" value="{{ old('precio') }}" required>
                    @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock') }}" required>
                    @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Imágenes (puedes seleccionar varias)</label>
                    <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
                    @error('imagenes.*') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection