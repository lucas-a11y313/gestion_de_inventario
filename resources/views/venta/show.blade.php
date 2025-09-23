@extends('template')

@section('title','Ver venta')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select o para hacer consultas javaScript--}}
@endpush

@section('content')
    <div class="container-fluid px-4">

        <h1 class="mt-4 text-center">Ver Venta</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
            <li class="breadcrumb-item active">Ver venta</li>
        </ol>
        <div class="d-flex justify-content-start mb-2">
            <a href="{{ route('ventas.print', $venta) }}" target="_blank" class="btn btn-primary"><i class="fa-solid fa-print"></i> Imprimir</a>

        </div>
    

        <div class="container w-100">{{--verificar si queda mejor con o sin esto: border border-3 border-primary rounded --}}

            <div class="card p-3 mb-4"> 

                <!-- Tipo comprobante -->
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-file"></i></span>
                            <input disabled type="text" class="form-control" value="Tipo de comprobante: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control" value="{{$venta->comprobante->tipo_comprobante}}">
                    </div>
                </div>

                <!-- Número de comprobante -->
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                            <input disabled type="text" class="form-control" value="Número de comprobante: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control" value="{{$venta->numero_comprobante}}">
                    </div>
                </div>

                <!-- Cliente -->
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-user-tie"></i></span>
                            <input disabled type="text" class="form-control" value="Cliente: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control" value="{{$venta->cliente->persona->razon_social}}">
                    </div>
                </div>

                <!-- User -->
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input disabled type="text" class="form-control" value="Vendedor: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control" value="{{$venta->user->name}}">
                    </div>
                </div>

                <!-- Fecha -->
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                            <input disabled type="text" class="form-control" value="Fecha: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control" value="{{\Carbon\Carbon::parse($venta->fecha_hora)->format('d-m-Y')}}">
                    </div>
                </div>

                <!-- Hora -->
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                            <input disabled type="text" class="form-control" value="Hora: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input disabled type="text" class="form-control" value="{{\Carbon\Carbon::parse($venta->fecha_hora)->format('H:i')}}">
                    </div>
                </div>

                <!-- Impuesto -->
                <!-- 
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-percent"></i></span>
                            <input disabled type="text" class="form-control" value="Impuesto: ">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <input id="input-impuesto" disabled type="text" class="form-control" value="{{-- $venta->impuesto --}}">
                    </div>
                </div>
                -->
            </div>

            <!-- Tabla -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Tabla detalle de la venta
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead class="bg-primary">{{-- no me funciona el text-white en mi thead por lo cual lo puse en cada <th> el text-white --}}
                            <tr>
                                <th class="text-white">Producto</th>
                                <th class="text-white">Cantidad</th>
                                <th class="text-white">Precio de venta</th>
                                <th class="text-white">Descuento</th>
                                <th class="text-white">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($venta->productos as $ventaproducto)
                                <tr>
                                    <td>
                                        {{$ventaproducto->nombre}}
                                    </td>
                                    <td>
                                        {{$ventaproducto->pivot->cantidad}}
                                    </td>
                                    <td>
                                        {{$ventaproducto->pivot->precio_venta}}
                                    </td>
                                    <td>
                                        {{$ventaproducto->pivot->descuento}}
                                    </td>
                                    <td class="td_subtotal">
                                        {{($ventaproducto->pivot->cantidad) * ($ventaproducto->pivot->precio_venta) - $ventaproducto->pivot->descuento}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                            </tr>
                            {{--
                            <tr>
                                <th colspan="4">Sumas:</th>
                                <th id="th_suma"></th>
                            </tr>
                            --}}
                            {{--
                            <tr>
                                <th colspan="4">IGV:</th>
                                <th id="th_igv"></th>
                            </tr>
                            --}}
                            <tr>
                                <th colspan="4">Total:</th>
                                <th id="th_total"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        //Variables
        let filasSubtotal = document.getElementsByClassName('td_subtotal');//getElementsByClassName('td_subtotal') te da una colección de elementos, pero no el contenido dentro de ellos.Obtenemos los elementos que contiene los <th> a través de la clase "td_subtotal"
        let cont = 0;//Va almacenar la sumatoria de los valores de filasSubtotal
        //let impuesto = $('#input-impuesto').val();//Traigo el valor que hay en el input con id="input-impuesto"
        $(document).ready(function(){
            calcularValores();
        });


        function calcularValores() {
            for (let i = 0; i < filasSubtotal.length; i++){
                cont = cont + parseFloat(filasSubtotal[i].innerHTML);//innerHTML obtiene el contenido HTML de cada uno de esos elementos, básicamente sirve para acceder al contenido de los elementos.
            }
            //$('#th_suma').html(cont);
            //$('#th_igv').html(impuesto);
            //$('#th_total').html(cont + parseFloat(impuesto));
            $('#th_total').html(cont);
        }
    </script>
@endpush