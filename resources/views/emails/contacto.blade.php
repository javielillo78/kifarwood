<!doctype html>
<html lang="es">
  <body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,sans-serif">
    <h2 style="margin:0 0 8px">Nuevo mensaje de contacto</h2>
    <p style="margin:0 0 6px"><strong>Asunto:</strong> {{ $data['asunto'] }}</p>
    <p style="margin:0 0 6px"><strong>De:</strong> {{ $data['user_name'] }} &lt;{{ $data['user_email'] }}&gt;</p>
    @if(!empty($data['telefono']))
      <p style="margin:0 0 6px"><strong>Teléfono:</strong> {{ $data['telefono'] }}</p>
    @endif
    <hr>
    <p style="white-space:pre-line">{{ $data['mensaje'] }}</p>
  </body>
</html>