<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Informe de Inventario</title>
  <style>
    body { font-family: sans-serif; font-size: 12px; }
    h1 { text-align: center; margin-bottom: 1rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { border: 1px solid #444; padding: 6px; }
    thead { background-color: #eee; }
    .text-center { text-align: center; }
  </style>
</head>
<body>
  <h1>Informe de Inventario</h1>
  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Marca</th>
        <th>Stock</th>
        <th>Costo unitario</th>
        <th>Costo total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($productos as $p)
        <tr>
          <td>{{ $p->codigo }}</td>
          <td>{{ $p->nombre }}</td>
          <td>{{ $p->marca_nombre }}</td>
          <td class="text-center">{{ $p->stock }}</td>
          <td class="text-center">{{ number_format($p->precio_reciente,2) }}</td>
          <td class="text-center">{{ number_format($p->stock*$p->precio_reciente,2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
