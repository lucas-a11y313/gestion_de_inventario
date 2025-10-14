@extends('template')

@section('title','Ver adquisicion')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select o para hacer consultas javaScript--}}
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Ver Adquisición</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('adquisiciones.index') }}">Adquisiciones</a></div>
                <div class="breadcrumb-item active">Ver adquisicion</div>
            </nav>

            <div class="flex gap-4 mb-6">
                <a href="{{ route('adquisiciones.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>

                @can('mostrar-adquisicion')
                    <a href="{{ route('adquisiciones.print', ['adquisicione' => $adquisicion]) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </a>
                @endcan
            </div>

            <div class="w-full">
                <div class="card p-4 mb-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        <!-- Proveedor -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-user text-gray-700"></i>
                                <span class="text-sm text-gray-700">Proveedor:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{$adquisicion->proveedore->persona->razon_social}}</span>
                            </div>
                        </div>

                        <!-- Tipo de Adquisición -->
                        @if($adquisicion->tipo_adquisicion)
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-tag text-gray-700"></i>
                                <span class="text-sm text-gray-700">Tipo:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{$adquisicion->tipo_adquisicion}}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Fecha -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-calendar-days text-gray-700"></i>
                                <span class="text-sm text-gray-700">Fecha:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{\Carbon\Carbon::parse($adquisicion->fecha_hora)->format('d-m-Y')}}</span>
                            </div>
                        </div>

                        <!-- Hora -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-clock text-gray-700"></i>
                                <span class="text-sm text-gray-700">Hora:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{\Carbon\Carbon::parse($adquisicion->fecha_hora)->format('H:i')}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card mb-6">
                    <div class="card-header">
                        <i class="fas fa-table mr-2"></i>
                        Tabla detalle de la adquisicion
                    </div>
                    <div class="card-body overflow-x-auto">
                        <table class="table table-striped">
                            <thead class="bg-blue-600">
                                <tr>
                                    <th style="color: white !important;">Producto</th>
                                    <th style="color: white !important;">Cantidad</th>
                                    <th style="color: white !important;">Precio de adquisicion</th>
                                    <th style="color: white !important;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adquisicion->productos as $adquisicionproducto)
                                    <tr>
                                        <td>{{$adquisicionproducto->nombre}}</td>
                                        <td>{{$adquisicionproducto->pivot->cantidad}}</td>
                                        <td>{{$adquisicionproducto->pivot->precio_compra}}</td>
                                        <td class="td_subtotal">{{$adquisicionproducto->pivot->cantidad * $adquisicionproducto->pivot->precio_compra}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total:</th>
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