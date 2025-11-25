@extends('adminlte::page')

@section('title', 'Editar producto')

@section('content_header')
    <h1>Editar producto</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Revisa los campos marcados.</p>
                </div>
            @endif
            <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" class="form-control" required>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>
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
                        <option value="{{ $prov->id }}"
                                {{ old('proveedor_id', $producto->proveedor_id) == $prov->id ? 'selected' : '' }}>
                            {{ $prov->nombre }}
                        </option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Precio (€)</label>
                    <input type="number" name="precio" class="form-control" step="0.01" min="0"
                           value="{{ old('precio', $producto->precio) }}" required>
                    @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" min="0"
                           value="{{ old('stock', $producto->stock) }}" required>
                    @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Añadir imágenes</label>
                    <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
                    @error('imagenes.*') <span class="text-danger">{{ $message }}</span> @enderror
                    <small class="text-muted">Se añadirán a las existentes.</small>
                </div>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Actualizar</button>
            </form>
            @if($producto->imagenes->count())
                    <div class="mt-3">
                        <label>Imágenes actuales</label>
                        <div class="d-flex flex-wrap" style="gap:12px">
                            @foreach($producto->imagenes as $img)
                                <div class="border p-2" style="width:140px">
                                    <img src="{{ asset($img->ruta) }}" alt="" style="width:100%;height:100px;object-fit:cover;border-radius:6px;">
                                    <form action="{{ route('admin.imagenes.destroy', $img) }}"
                                        method="POST"
                                        class="form-eliminar mt-2"
                                        data-nombre="imagen #{{ $img->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-danger btn-block">
                                            <i class="fas fa-trash-alt"></i> Borrar
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
        </div>
    </div>
@endsection