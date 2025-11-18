@php
    $ivaPercent = $ivaPercent ?? 21;
    $total      = (float)($total ?? ($pedido->total ?? 0));
    $base       = $base  ?? round($total / (1 + $ivaPercent/100), 2);
    $iva        = $iva   ?? round($total - $base, 2);

    $numero  = str_pad($pedido->id ?? 0, 6, '0', STR_PAD_LEFT);

    $fechaBase = $pedido->fecha_pedido ?? $pedido->created_at;
    $fecha     = $fechaBase ? \Carbon\Carbon::parse($fechaBase)->format('d/m/Y') : '';

    $cliente   = $pedido->usuario ?? auth()->user();
    $detalles  = $pedido->detalles ?? collect();

    // Más filas para que el pie baje un poco más
    $maxFilasDetalle = 22;
@endphp
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura #{{ $pedido->id }}</title>
    <style>
        @page {
            margin: 18px 20px;
        }
        * { box-sizing:border-box; }
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 10px;
            color:#000;
        }
        .page {
            width:100%;
            border:1px solid #003366;
            padding:8px 10px 4px 10px;
        }
        .row { display:flex; width:100%; }
        .col { flex:1 1 0; }

        .logo { width: 95px; height:auto; }

        .empresa {
            font-size: 11px;
            font-weight:700;
            line-height:1.2;
        }
        .empresa small {
            font-weight:400;
            font-size:9px;
        }

        .header-blue {
            background:#0057a4;
            color:#fff;
            text-align:center;
            font-weight:800;
            font-size:18px;
            letter-spacing:.5px;
            padding:6px 0;
        }

        .subband {
            background:#0057a4;
            color:#fff;
            font-size:10px;
            font-weight:700;
            padding:3px 6px;
        }

        .box {
            border:1px solid #003366;
            padding:4px 6px;
            font-size:9px;
        }
        .box-label {
            background:#0057a4;
            color:#fff;
            font-weight:700;
            padding:3px 6px;
            font-size:9px;
        }

        table { border-collapse:collapse; width:100%; }
        th, td { padding:3px 3px; }

        .detalle-header {
            background:#0057a4;
            color:#fff;
            font-weight:700;
            font-size:9px;
        }
        .detalle-header th {
            border-right:1px solid #ffffff;
        }
        .detalle-header th:last-child {
            border-right:0;
        }

        .detalle-body td {
            border-bottom:1px solid #d0d0d0;
            font-size:9px;
            line-height:1.1;
        }

        .right { text-align:right; }
        .center { text-align:center; }

        .pie-top {
            margin-top:6px;
            border-top:1px solid #003366;
            padding-top:4px;
        }

        .tot-band {
            width:100%;
            border-top:1px solid #003366;
            border-bottom:1px solid #003366;
            margin-top:4px;
        }
        .tot-cell {
            border-right:1px solid #003366;
            padding:4px 4px;
            font-size:9px;
        }
        .tot-cell:last-child {
            border-right:0;
        }
        .tot-head {
            font-weight:700;
            font-size:9px;
        }
        .tot-val {
            margin-top:2px;
            font-weight:700;
            font-size:10px;
        }

        .small { font-size:8px; }
    </style>
</head>
<body>
<div class="page">

    <div class="row" style="align-items:center; margin-bottom:6px;">
        <div class="col" style="max-width:120px;">
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Kifar Woods">
        </div>
        <div class="col empresa">
            KIFAR WOODS ESTUDIOS, S.L.<br>
            <small>CTRA. A - 440 KM.7, LA · 14120 FUENTE PALMERA</small><br>
            <small>B67655993</small>
        </div>
        {{-- <div class="col">
            <div class="header-blue" style="margin-left:10px;">FACTURA</div>
        </div> --}}
    </div>

    <div class="subband" style="margin-bottom:4px;">
        CLIENTE
    </div>

    <div class="row" style="margin-bottom:3px;">
        <div class="col box" style="min-height:40px;">
            <strong>{{ $cliente?->name ?? '—' }}</strong><br>
            <span class="small">{{ $cliente?->email ?? '—' }}</span>
        </div>
    </div>

    <div class="row" style="margin-bottom:6px;">
        <div class="col" style="max-width:140px;">
            <div class="box-label">Número</div>
            <div class="box center">{{ $numero }}</div>
        </div>
        <div class="col" style="max-width:140px;">
            <div class="box-label">Fecha</div>
            <div class="box center">{{ $fecha }}</div>
        </div>
    </div>

    <table>
        <thead class="detalle-header">
            <tr>
                <th style="width:10%;">Código</th>
                <th>Descripción</th>
                <th style="width:8%;" class="center">Cant.</th>
                <th style="width:15%;" class="right">Preci/u</th>
                <th style="width:8%;" class="center">Dto.</th>
                <th style="width:8%;" class="center">%I</th>
                <th style="width:15%;" class="right">Importe</th>
            </tr>
        </thead>
        <tbody class="detalle-body">
            @foreach($detalles as $d)
                @php
                    $codigo  = 'PROD-'.str_pad($d->producto_id ?? 0, 5, '0', STR_PAD_LEFT);
                    $nombre  = $d->producto->nombre ?? '—';
                    $cant    = (int)($d->cantidad ?? 0);
                    $precioU = (float)($d->precio_unitario ?? 0);
                    $importe = (float)($d->subtotal ?? ($precioU * $cant));
                @endphp
                <tr>
                    <td class="center">{{ $codigo }}</td>
                    <td>{{ $nombre }}</td>
                    <td class="center">{{ $cant }}</td>
                    <td class="right">{{ number_format($precioU, 2, ',', '.') }} €</td>
                    <td class="center">0</td>
                    <td class="center">{{ $ivaPercent }}</td>
                    <td class="right">{{ number_format($importe, 2, ',', '.') }} €</td>
                </tr>
            @endforeach

            @for($i = $detalles->count(); $i < $maxFilasDetalle; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="pie-top">
        <table class="tot-band">
            <tr>
                <td class="tot-cell" style="width:15%;">
                    <div class="tot-head">Descuento</div>
                    <div class="tot-val">0 %</div>
                </td>
                <td class="tot-cell" style="width:25%;">
                    <div class="tot-head">Base imponible</div>
                    <div class="tot-val">{{ number_format($base,2,',','.') }} €</div>
                </td>
                <td class="tot-cell" style="width:15%;">
                    <div class="tot-head">% IVA</div>
                    <div class="tot-val">{{ $ivaPercent }} %</div>
                </td>
                <td class="tot-cell" style="width:20%;">
                    <div class="tot-head">IVA</div>
                    <div class="tot-val">{{ number_format($iva,2,',','.') }} €</div>
                </td>
                <td class="tot-cell" style="width:10%;">
                    <div class="tot-head">RE</div>
                    <div class="tot-val">0,00 €</div>
                </td>
                <td class="tot-cell" style="width:15%;">
                    <div class="tot-head">TOTAL</div>
                    <div class="tot-val">{{ number_format($total,2,',','.') }} €</div>
                </td>
            </tr>
        </table>

        <p class="small" style="margin-top:6px;">
            Precios con IVA incluido. Documento generado automáticamente.
        </p>
    </div>
</div>
</body>
</html>