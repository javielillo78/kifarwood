<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Confirmación de pedido #{{ $pedido->id }}</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;color:#222}
        .box{border:1px solid #eee; padding:12px; border-radius:8px}
        .muted{color:#666; font-size:12px}
        table{border-collapse:collapse; width:100%}
        th,td{border:1px solid #eee; padding:6px; font-size:13px}
        th{background:#f7f7f7; text-align:left}
        .right{text-align:right}
    </style>
</head>
<body>
    <h2>¡Gracias por tu compra!</h2>

    <p>Hemos recibido tu pedido <strong>#{{ $pedido->id }}</strong>. Adjuntamos la factura en PDF.</p>

    <div class="box">
        <p><strong>Resumen del pedido</strong></p>
        <table>
            <thead>
            <tr>
                <th>Producto</th>
                <th class="right" style="width:110px">Precio</th>
                <th class="right" style="width:80px">Ud.</th>
                <th class="right" style="width:120px">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pedido->detalles as $d)
                <tr>
                    <td>{{ $d->producto->nombre ?? '—' }}</td>
                    <td class="right">{{ number_format((float)$d->precio_unitario,2,',','.') }} €</td>
                    <td class="right">{{ (int)$d->cantidad }}</td>
                    <td class="right">{{ number_format((float)$d->subtotal,2,',','.') }} €</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" class="right">Base imponible</th>
                <th class="right">{{ number_format((float)$base,2,',','.') }} €</th>
            </tr>
            <tr>
                <th colspan="3" class="right">IVA (21%)</th>
                <th class="right">{{ number_format((float)$iva,2,',','.') }} €</th>
            </tr>
            <tr>
                <th colspan="3" class="right">Total</th>
                <th class="right">{{ number_format((float)$total,2,',','.') }} €</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <p class="muted">Si no has realizado este pedido, ignora este mensaje.</p>
</body>
</html>
