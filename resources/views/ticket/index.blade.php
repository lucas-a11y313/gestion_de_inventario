@extends('template')

@section('title', 'Ticket de Venta')

@push('css')
    <style>
        .ticket-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Centrar verticalmente */
        }
        .ticket {
            font-family: Arial, sans-serif;
            text-align: center;
            width: 300px; /* Cambiar a 300px para impresora de 80mm, cambiar a 220px para impresora de 58mm */
            margin: auto;
            border: 1px solid #000;
            padding: 10px;
            font-size: 12px;
            word-wrap: break-word;
            overflow: hidden;
        }
        h2, h3 {
            margin: 5px 0;
            font-size: 14px;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }
        td {
            padding: 2px;
            word-wrap: break-word;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
            text-align: right;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .ticket {
                width: 220px;
                border: none;
            }
        }
    </style>
@endpush

@section('content')
<div class="ticket-container">
    <div class="ticket">
        <h3>TICKET DE VENTA</h3>
        <h2>EMPRESA DEMO</h2>
        <p>TEL: 021 640-000<br>Ciudad del Este</p>
        <div class="line"></div>
        <?php 
            use Carbon\Carbon;//Importamos la clase Carbon 
            $fecha_hora = Carbon::now('America/Asuncion')->toDateTimeString();//es una función de la librería Carbon en Laravel y PHP que se usa para obtener la fecha y hora actual. Ponemos que queremos de la zona horaria de Paraguay
        ?>
        <p>{{ $fecha_hora }}</p>
        <p>Número Venta: 3.648</p>
        <p>Cliente: SIN NOMBRE</p>
        <p>Vendedor: ADMIN</p>
        <div class="line"></div>
        <table>
            <tr>
                <td><strong>Código</strong></td>
                <td><strong>Detalle</strong></td>
                <td><strong>Cant.</strong></td>
                <td><strong>Unitario</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            <tr>
                <td>rfdsfsg dgfgsd</td>
                <td>Crema limpiadora gfdgfd </td>
                <td>1000</td>
                <td>10000000</td>
                <td>10000000</td>
            </tr>
            <tr>
                <td>340-FCDR</td>
                <td>Crema limpiadora gfdgfd </td>
                <td>100</td>
                <td>1.000.000</td>
                <td>1.000.000</td>
            </tr>
        </table>
        <div class="line"></div>
        <p>Efectivo: 80000</p>
        <p>Vuelto: 0</p>
        <div class="line"></div>
        <p class="total">TOTAL GS: 80000</p>
        <p>OCHENTA MIL GS.</p>
        <div class="line"></div>
        <p>***GRACIAS POR ELEGIRNOS***</p>
    </div>
</div>
@endsection

@push('js')

@endpush
