@extends('adminlte::page')

@section('title', 'Nuevo usuario')

@section('content_header')
    <h1>Nuevo usuario</h1>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Revisa los campos marcados.</p>
                </div>
            @endif
            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" class="form-control" required>
                        <option value="cliente" {{ old('rol') === 'cliente' ? 'selected' : '' }}>cliente</option>
                        <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>admin</option>
                    </select>
                    @error('rol') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input name="password" type="password" class="form-control" required>
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Repite la contraseña</label>
                    <input name="password_confirmation" type="password" class="form-control" required>
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                <button class="btn btn-primary">Crear usuario</button>
            </form>
        </div>
    </div>
@endsection