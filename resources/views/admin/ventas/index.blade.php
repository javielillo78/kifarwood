@extends('adminlte::page')

@section('title', 'Gestión de ventas')

@section('content_header')
    <h1>Gestión de ventas</h1>
@stop

@section('content')

    @if(session('login_ok'))
        <div class="alert alert-success">
            {{ session('login_ok') }}
        </div>
    @endif

    @if(session('ventas_ok'))
        <div class="alert alert-success">
            {{ session('ventas_ok') }}
        </div>
    @endif

    @if(session('ventas_err'))
        <div class="alert alert-danger">
            {{ session('ventas_err') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ventas.index') }}" class="form-inline mb-2">

                <div class="form-group mr-3 mb-2">
                    <label for="user_id" class="mr-2">Usuario</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">-- Todos --</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ (string)$userId === (string)$u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="from" class="mr-2">Desde</label>
                    <input type="date" name="from" id="from" value="{{ $from }}" class="form-control">
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="to" class="mr-2">Hasta</label>
                    <input type="date" name="to" id="to" value="{{ $to }}" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mb-2 mr-2">
                    <i class="fa fa-filter"></i> Filtrar
                </button>

                <a href="{{ route('admin.ventas.index') }}" class="btn btn-default mb-2">
                    Limpiar
                </a>
            </form>

            @if($userId && !is_null($importeTotalUsuario))
                <div class="alert alert-info">
                    Importe total gastado por el usuario seleccionado:
                    <strong>{{ number_format($importeTotalUsuario, 2, ',', '.') }} €</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de ventas</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th class="text-right">Importe</th>
                        <th>Estado</th>
                        <th style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        @php
                            $fecha = $pedido->fecha_pedido ?? $pedido->created_at;
                        @endphp
                        <tr>
                            <td>#{{ $pedido->id }}</td>
                            <td>
                                {{ $fecha ? \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td>
                                {{ $pedido->usuario->name ?? '—' }}<br>
                                <small class="text-muted">{{ $pedido->usuario->email ?? '' }}</small>
                            </td>
                            <td class="text-right">
                                {{ number_format($pedido->total, 2, ',', '.') }} €
                            </td>
                            <td>
                                <span class="badge badge-success">{{ ucfirst($pedido->estado) }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('public.cesta.factura', $pedido) }}" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay ventas con los filtros actuales.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pedidos->hasPages())
            <div class="card-footer">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
@stop
