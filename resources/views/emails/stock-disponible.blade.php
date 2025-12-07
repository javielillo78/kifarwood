<p>Hola {{ $user->name }},</p>

<p>¡Buenas noticias! El producto <strong>{{ $producto->nombre }}</strong> vuelve a tener stock disponible.</p>

<p>
    Precio: <strong>{{ number_format($producto->precio,2,',','.') }} €</strong>
</p>

<p>
    Puedes verlo y comprarlo aquí:<br>
    <a href="{{ route('public.productos.show', $producto) }}">
        Ver producto
    </a>
</p>

<p>Gracias por confiar en nosotros.</p>