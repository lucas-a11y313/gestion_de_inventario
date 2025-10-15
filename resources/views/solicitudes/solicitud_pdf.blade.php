<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitud #{{ $solicitud->id }}</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      margin: 20px;
    }
    .header {
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #2563eb;
      overflow: hidden;
      min-height: 70px;
    }
    .header-logo {
      float: left;
      width: 150px;
      height: 70px;
      margin-right: 20px;
    }
    .header-logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .header-logo svg {
      width: 60px;
      height: 60px;
    }
    .header-text {
      padding-top: 15px;
    }
    .header-title {
      font-size: 18px;
      font-weight: bold;
      color: #2563eb;
      margin: 0 0 5px 0;
    }
    .header-subtitle {
      font-size: 11px;
      color: #666;
      margin: 0;
    }
    h1 {
      text-align: center;
      margin-bottom: 1rem;
      margin-top: 1rem;
      color: #333;
      font-size: 16px;
    }
    .info-section {
      margin-bottom: 1.5rem;
      background-color: #f9f9f9;
      padding: 10px;
      border-radius: 5px;
    }
    .info-row {
      display: flex;
      margin-bottom: 8px;
    }
    .info-label {
      font-weight: bold;
      width: 150px;
      color: #555;
    }
    .info-value {
      flex: 1;
      color: #000;
    }
    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 11px;
      font-weight: bold;
    }
    .badge-retiro {
      background-color: #fee;
      color: #c00;
    }
    .badge-prestamo {
      background-color: #def;
      color: #05c;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      border: 1px solid #444;
      padding: 8px;
      text-align: left;
    }
    thead {
      background-color: #2563eb;
      color: white;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .footer {
      margin-top: 2rem;
      text-align: center;
      font-size: 10px;
      color: #777;
    }
  </style>
</head>
<body>
  <!-- Encabezado con logo -->
  <div class="header">
    <div class="header-logo">
      @if(file_exists(public_path('img/logo.png')))
        <img src="{{ public_path('img/logo.png') }}" alt="Logo">
      @else
        <!-- Icono por defecto SVG si no hay logo -->
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect width="24" height="24" rx="4" fill="#2563eb"/>
          <path d="M7 12L10 15L17 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      @endif
    </div>
  </div>

  <h1>Solicitud de {{ ucfirst($solicitud->tipo_solicitud) }}</h1>

  <div class="info-section">
    <div class="info-row">
      <span class="info-label">Usuario:</span>
      <span class="info-value">{{ $solicitud->user->name }} ({{ $solicitud->user->email }})</span>
    </div>
    <div class="info-row">
      <span class="info-label">Tipo de solicitud:</span>
      <span class="info-value">
        <span class="badge {{ $solicitud->tipo_solicitud == 'retiro' ? 'badge-retiro' : 'badge-prestamo' }}">
          {{ ucfirst($solicitud->tipo_solicitud) }}
        </span>
      </span>
    </div>
    <div class="info-row">
      <span class="info-label">Fecha y hora:</span>
      <span class="info-value">{{ \Carbon\Carbon::parse($solicitud->fecha_hora)->format('d-m-Y H:i') }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Razón del {{ $solicitud->tipo_solicitud }}:</span>
      <span class="info-value">{{ $solicitud->razon }}</span>
    </div>
  </div>

  <h3 style="margin-top: 1.5rem; color: #333;">Detalle de productos</h3>
  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th class="text-center">Cantidad</th>
        <th class="text-right">Precio de compra</th>
        <th class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @php
        $total = 0;
      @endphp
      @foreach($solicitud->productos as $producto)
        @php
          $subtotal = $producto->pivot->cantidad * $producto->pivot->precio_compra;
          $total += $subtotal;
        @endphp
        <tr>
          <td>{{ $producto->nombre }}</td>
          <td class="text-center">{{ $producto->pivot->cantidad }}</td>
          <td class="text-right">{{ number_format($producto->pivot->precio_compra, 2) }}</td>
          <td class="text-right">{{ number_format($subtotal, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr style="background-color: #f3f4f6; font-weight: bold;">
        <td colspan="3" class="text-right">TOTAL:</td>
        <td class="text-right">{{ number_format($total, 2) }}</td>
      </tr>
    </tfoot>
  </table>

  <div style="margin-top: 100px; text-align: center; font-size: 12px;">
    <div style="display: inline-block; width: 250px; border-top: 1px solid #333; padding-top: 8px;">
      <strong>Nombre y Apellido</strong>
    </div>
    <div style="display: inline-block; width: 80px;"></div> <!-- Espacio entre las dos firmas -->
    <div style="display: inline-block; width: 250px; border-top: 1px solid #333; padding-top: 8px;">
      <strong>Firma</strong>
    </div>
  </div>

  <div class="footer">
    Generado el {{ now()->format('d-m-Y H:i:s') }}
  </div>
</body>
</html>
