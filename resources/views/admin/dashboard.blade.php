@php
    use App\Models\Pedido;

    $nuevosPedidos = Pedido::where('estado', 'pagado')
        ->where('revisado_admin', false)
        ->count();
@endphp

@extends('adminlte::page')

@section('title', 'Panel de Administración')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Panel de Administración · Kifar Wood Studios</h1>
        <a href="{{ route('public.index') }}" class="btn">
            <i class="fas fa-globe"></i> Ir al sitio
        </a>
    </div>
@endsection

@section('content')
    @if($nuevosPedidos > 0)
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <strong>¡Tienes {{ $nuevosPedidos }} pedido(s) nuevo(s)!</strong><br>
                Revisa la sección de <strong>Gestión de ventas</strong>.
            </div>
            <a href="{{ route('admin.ventas.index') }}" class="btn btn-sm btn-primary">
                Ir a Gestión de ventas
            </a>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h2 class="h4 mb-3">Bienvenido, {{ Auth::user()->name }}</h2>
            <p class="text-muted">
                Este panel es el área de gestión interna del proyecto <strong>Kifar Wood Studios</strong>.
                Aquí el administrador controla los datos del sistema:
            </p>
            <ul class="mb-4">
                <li><strong>Usuarios:</strong> alta/baja, cambio de rol (cliente / admin), control de acceso.</li>
                <li><strong>Categorías:</strong> definición de líneas de productos (mesas, decoración, etc.).</li>
                <li><strong>Productos:</strong> catálogo con precio, stock e información comercial.</li>
            </ul>
            <p>
                El objetivo es ofrecer una solución completa de gestión para una carpintería donde el cliente podrá:
            </p>
            <ul class="mb-4">
                <li>Registrarse y verificar su cuenta por correo electrónico.</li>
                <li>Acceder a su área privada y en el futuro ver pedidos, presupuestos y reservas de servicios.</li>
                <li>Realizar solicitudes personalizadas de trabajo a medida.</li>
            </ul>
            <p class="text-muted mb-0">
                Esta versión es la parte administrativa del Proyecto DAW.  
                La parte pública (catálogo, reservas, etc.) está en desarrollo y
                será accesible a los clientes verificados.
            </p>
        </div>
    </div>
@endsection