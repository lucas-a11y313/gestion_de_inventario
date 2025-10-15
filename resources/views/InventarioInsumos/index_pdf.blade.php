<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Inventario de Insumos</title>
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
    h1 {
      text-align: center;
      margin-bottom: 1.5rem;
      margin-top: 1rem;
      color: #333;
      font-size: 18px;
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
      font-size: 11px;
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

  <h1>Reporte de Inventario de Insumos</h1>

  <table>
    <thead>
      <tr>
        <th>Código del Producto</th>
        <th>Nombre del Producto</th>
        <th>Cantidad</th>
        <th>Costo Promedio</th>
      </tr>
    </thead>
    <tbody>
      @forelse($insumos as $insumo)
        <tr>
          <td>{{ $insumo->codigo }}</td>
          <td>{{ $insumo->nombre }}</td>
          <td>{{ $insumo->stock }}</td>
          <td>
              @php
                  $preciosAdquisiciones = $insumo->adquisiciones->pluck('pivot.precio_compra')->filter();
                  $preciosCompras = $insumo->compras->pluck('pivot.precio_compra')->filter();
                  $todosPrecios = $preciosAdquisiciones->concat($preciosCompras);
                  $precioPromedio = $todosPrecios->isNotEmpty() ? $todosPrecios->avg() : 0;
              @endphp
              ${{ number_format($precioPromedio, 2) }}
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" style="text-align: center;">No hay insumos registrados.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    Generado el {{ now()->format('d-m-Y H:i:s') }}
  </div>
</body>
</html>
