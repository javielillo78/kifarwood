@extends('adminlte::page')

@section('title','Editar proveedor')

@section('content_header')
  <h1>Editar proveedor</h1>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">Revisa los campos.</div>
      @endif

      <form action="{{ route('admin.proveedores.update',$proveedor) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>Nombre</label>
          <input name="nombre" class="form-control" value="{{ old('nombre',$proveedor->nombre) }}" required>
          @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
        <button class="btn btn-primary">Actualizar</button>
      </form>
    </div>
  </div>
@endsection