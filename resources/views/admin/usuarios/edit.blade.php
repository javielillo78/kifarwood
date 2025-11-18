@extends('adminlte::page')

@section('title', 'Editar usuario')

@section('content_header')
    <h1>Editar usuario</h1>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Revisa los campos marcados.</p>
                </div>
            @endif
            <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="name" class="form-control"
                           value="{{ old('name', $usuario->name) }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" value="{{ $usuario->email }}" disabled>
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" class="form-control" required>
                        <option value="cliente" {{ old('rol', $usuario->rol) === 'cliente' ? 'selected' : '' }}>cliente</option>
                        <option value="admin" {{ old('rol', $usuario->rol) === 'admin' ? 'selected' : '' }}>admin</option>
                    </select>
                    @error('rol') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection