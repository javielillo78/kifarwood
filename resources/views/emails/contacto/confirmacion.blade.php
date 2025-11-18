<!doctype html>
<html lang="es">
  <body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; color:#222;">
    <h2 style="margin:0 0 12px">¡Gracias, {{ $payload['user_name'] }}!</h2>
    <p style="margin:0 0 10px">
      Hemos recibido tu mensaje y te responderemos lo antes posible.
    </p>

    <p style="margin:0 0 6px"><strong>Asunto:</strong> {{ $payload['asunto'] }}</p>

    @if(!empty($payload['telefono']))
      <p style="margin:0 0 6px"><strong>Teléfono:</strong> {{ $payload['telefono'] }}</p>
    @endif

    <div style="margin:12px 0; padding:12px; background:#f6f6f6; border-radius:8px;">
      <div style="margin-bottom:6px; font-weight:700;">Tu mensaje:</div>
      <div style="white-space:pre-wrap;">{{ $payload['mensaje'] }}</div>
    </div>

    <p style="margin:16px 0 0; font-size:13px; color:#666;">
      Si no has enviado este mensaje, ignora este correo.
    </p>
  </body>
</html>
