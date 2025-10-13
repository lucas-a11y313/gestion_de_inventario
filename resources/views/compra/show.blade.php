@extends('template')

@section('title','Ver compra')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select o para hacer consultas javaScript--}}
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Ver Compra</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></div>
                <div class="breadcrumb-item active">Ver compra</div>
            </nav>

            <div class="w-full">
                <div class="card p-4 mb-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        <!-- Tipo comprobante -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-file text-gray-700"></i>
                                <span class="text-sm text-gray-700">Tipo de comprobante:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{$compra->comprobante->tipo_comprobante}}</span>
                            </div>
                        </div>

                        <!-- Número de comprobante -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-hashtag text-gray-700"></i>
                                <span class="text-sm text-gray-700">Número de comprobante:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{$compra->numero_comprobante}}</span>
                            </div>
                        </div>

                        <!-- Proveedor -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-user text-gray-700"></i>
                                <span class="text-sm text-gray-700">Proveedor:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{$compra->proveedore->persona->razon_social}}</span>
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-calendar-days text-gray-700"></i>
                                <span class="text-sm text-gray-700">Fecha:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{\Carbon\Carbon::parse($compra->fecha_hora)->format('d-m-Y')}}</span>
                            </div>
                        </div>

                        <!-- Hora -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-clock text-gray-700"></i>
                                <span class="text-sm text-gray-700">Hora:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{\Carbon\Carbon::parse($compra->fecha_hora)->format('H:i')}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card mb-6">
                    <div class="card-header">
                        <i class="fas fa-table mr-2"></i>
                        Tabla detalle de la compra
                    </div>
                    <div class="card-body overflow-x-auto">
                        <table class="table table-striped">
                            <thead class="bg-blue-600">
                                <tr>
                                    <th class="text-white">Producto</th>
                                    <th class="text-white">Cantidad</th>
                                    <th class="text-white">Precio de compra</th>
                                    <th class="text-white">Precio de venta</th>
                                    <th class="text-white">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($compra->productos as $compraproducto)
                                    <tr>
                                        <td>{{$compraproducto->nombre}}</td>
                                        <td>{{$compraproducto->pivot->cantidad}}</td>
                                        <td>{{$compraproducto->pivot->precio_compra}}</td>
                                        <td>{{$compraproducto->pivot->precio_venta}}</td>
                                        <td class="td_subtotal">{{($compraproducto->pivot->cantidad) * ($compraproducto->pivot->precio_compra)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                </tr>
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
                cont += parseFloat(filasSubtotal[i].innerHTML);
            }
            $('#th_total').html(cont);
        }
    </script>
@endpush