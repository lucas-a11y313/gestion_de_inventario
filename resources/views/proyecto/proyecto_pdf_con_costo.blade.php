<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Proyecto #{{ $proyecto->id }}</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      margin: 20px;
    }
    .header {
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #6366f1;
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
    h1 {
      text-align: center;
      margin-bottom: 1rem;
      margin-top: 1rem;
      color: #333;
      font-size: 18px;
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
      background-color: #6366f1;
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
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect width="24" height="24" rx="4" fill="#6366f1"/>
          <path d="M7 12L10 15L17 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      @endif
    </div>
  </div>

  <h1>Proyecto: {{ $proyecto->nombre }}</h1>

  <div class="info-section">
    <div class="info-row">
      <span class="info-label">Fecha de ejecución:</span>
      <span class="info-value">{{ $proyecto->fecha_ejecucion ? \Carbon\Carbon::parse($proyecto->fecha_ejecucion)->format('d/m/Y') : 'No especificada' }}</span>
    </div>
    @if($proyecto->descripcion)
    <div class="info-row">
      <span class="info-label">Descripción:</span>
      <span class="info-value">{{ $proyecto->descripcion }}</span>
    </div>
    @endif
    <div class="info-row">
      <span class="info-label">Total de productos:</span>
      <span class="info-value">{{ $proyecto->productos->count() }} {{ $proyecto->productos->count() == 1 ? 'producto' : 'productos' }}</span>
    </div>
  </div>

  <h3 style="margin-top: 1.5rem; color: #333;">Detalle de productos</h3>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Código</th>
        <th>Producto</th>
        <th class="text-center">Cantidad Requerida</th>
        <th class="text-right">Valor Unitario</th>
        <th class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @php
        $total = 0;
      @endphp
      @foreach($proyecto->productos as $index => $producto)
        @php
          $precioUnitario = $producto->pivot->cantidad > 0 ?
                          ($producto->adquisiciones->first() ?
                            $producto->adquisiciones->first()->pivot->precio_compra : 0) : 0;
          $subtotal = $producto->pivot->cantidad * $precioUnitario;
          $total += $subtotal;
        @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $producto->codigo }}</td>
          <td>{{ $producto->nombre }}</td>
          <td class="text-center">{{ $producto->pivot->cantidad }}</td>
          <td class="text-right">{{ number_format($precioUnitario, 2) }}</td>
          <td class="text-right">{{ number_format($subtotal, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr style="background-color: #f3f4f6;">
        <td colspan="3" class="text-right"><strong>Total de productos diferentes:</strong></td>
        <td colspan="3"><strong>{{ $proyecto->productos->count() }}</strong></td>
      </tr>
      <tr style="background-color: #f3f4f6;">
        <td colspan="3" class="text-right"><strong>Cantidad total de items:</strong></td>
        <td colspan="3"><strong>{{ $proyecto->productos->sum('pivot.cantidad') }}</strong></td>
      </tr>
      <tr style="background-color: #e0e7ff; font-weight: bold;">
        <td colspan="5" class="text-right">TOTAL DEL PROYECTO:</td>
        <td class="text-right">{{ number_format($total, 2) }}</td>
      </tr>
    </tfoot>
  </table>

  <div class="footer">
    Generado el {{ now()->format('d-m-Y H:i:s') }}
  </div>
</body>
</html>
