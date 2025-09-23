@php
    use Carbon\Carbon; // Importamos la clase Carbon
@endphp


<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ticket de Venta</title>
  <style>
    /* 1) Quita el height:100vh para que el ticket mida sólo lo que ocupa */
    .ticket-container {
      display: flex;
      justify-content: center;
      align-items: start; /* o center si lo quieres centrado vertical, no importa tanto */
      /* height: 100vh;  <-- ELIMÍNALO */
      padding-top: 10px;   /* opcional para que no quede pegado */
    }

    .ticket {
        font-family: Arial, sans-serif;
        text-align: center;
        width: 300px; /* 300px para impresora de 80mm, 220px para 58mm */
        margin: auto;
        border: 1px solid #000;
        padding: 10px;
        font-size: 12px;
        word-wrap: break-word;
        overflow: hidden;
        position: relative;
    }

    /* 2) Oculta el botón al imprimir */
    .no-print {
      display: block;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }

    /* 3) Especifica el tamaño de página/ticket para que no haga saltos de página */
    @page {
      size: 80mm auto; /* ancho de 80 mm por altura automática. 300px para impresora de 80mm, 220px para 58mm */
      margin: 0;
    }
    body {
      margin: 0;
      padding: 0;
    }
  </style>
  
</head>
<body>
  <div class="ticket-container">
        <div class="ticket">
            {{-- Botón para imprimir, sólo en pantalla --}}
            <button onclick="window.print()"
                    class="no-print"
                    style="position:absolute; top:10px; right:10px; padding:4px 8px;">
                🖨 Imprimir
            </button>

            <h3>TICKET DE VENTA</h3>
            <h2>CAMPOSEG SEGURIDAD ELECTRÓNICA</h2>
            <p>TEL: +595 983 422025<br>San Alberto</p>
            <div class="line"></div>

            @php
                $fecha_hora = Carbon::now('America/Asuncion')->toDateTimeString();
            @endphp
            <p>{{ $fecha_hora }}</p>
            <p>Número Venta: {{ $venta->id }}</p>
            <p>Cliente: {{ $venta->cliente->persona->razon_social ?? 'SIN NOMBRE' }}</p>
            <p>Vendedor: {{ $venta->user->name ?? 'ADMIN' }}</p>
            <div class="line"></div>

            <table>
                <tr>
                    <td><strong>Código</strong></td>
                    <td><strong>Detalle</strong></td>
                    <td><strong>Cant.</strong></td>
                    <td><strong>Unitario</strong></td>
                    <td><strong>Total</strong></td>
                </tr>
                @foreach($venta->productos as $item)
                <tr>
                    <td>{{ $item->codigo }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->pivot->cantidad }}</td>
                    <td>{{ number_format($item->pivot->precio_venta, 2) }}</td>
                    <td>{{ number_format($item->pivot->cantidad * $item->pivot->precio_venta, 2) }}</td>
                </tr>
                @endforeach
            </table>

            <div class="line"></div>
            {{--<p>Efectivo:</p>
            <p>Vuelto:</p>--}}
            <div class="line"></div>
            <p class="total">TOTAL GS: {{ number_format($venta->total, 2) }}</p>
            <p>{{ $venta->total }}</p>
            <div class="line"></div>
            <p>***GRACIAS POR ELEGIRNOS***</p>
        </div>
  </div>

  <script>
    window.onload = function() {
      window.print();
    };
  </script>
</body>
</html>
