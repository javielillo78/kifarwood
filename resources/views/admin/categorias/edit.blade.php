@extends('adminlte::page')

@section('title', 'Editar categoría')

@section('content_header')
    <h1>Editar categoría</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Revisa los campos marcados.</p>
                </div>
            @endif
            <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="nombre" class="form-control" value="{{ old('nombre', $categoria->nombre) }}" required>
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection