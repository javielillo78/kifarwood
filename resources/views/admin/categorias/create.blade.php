@extends('adminlte::page')

@section('title', 'Nueva categoría')

@section('content_header')
    <h1>Nueva categoría</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Revisa los campos marcados.</p>
                </div>
            @endif
            <form action="{{ route('admin.categorias.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection