@extends('template')

@section('title','Ver solicitud')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Ver Solicitud</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('solicitudes.index') }}">Solicitudes</a></div>
                <div class="breadcrumb-item active">Ver solicitud</div>
            </nav>

            <!-- Botones de acción -->
            <div class="flex gap-4 mb-6">
                <a href="{{ route('solicitudes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>

                @can('mostrar-solicitud')
                    <a href="{{ route('solicitudes.print', ['solicitude' => $solicitud]) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </a>
                @endcan
            </div>

            <div class="w-full">
                <div class="card p-4 mb-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        <!-- Usuario -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-user text-gray-700"></i>
                                <span class="text-sm text-gray-700">Usuario:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{$solicitud->user->name}} ({{$solicitud->user->email}})</span>
                            </div>
                        </div>

                        <!-- Tipo de solicitud -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-file text-gray-700"></i>
                                <span class="text-sm text-gray-700">Tipo de solicitud:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $solicitud->tipo_solicitud == 'retiro' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($solicitud->tipo_solicitud) }}
                                </span>
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-calendar-days text-gray-700"></i>
                                <span class="text-sm text-gray-700">Fecha:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{\Carbon\Carbon::parse($solicitud->fecha_hora)->format('d-m-Y')}}</span>
                            </div>
                        </div>

                        <!-- Hora -->
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-clock text-gray-700"></i>
                                <span class="text-sm text-gray-700">Hora:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{\Carbon\Carbon::parse($solicitud->fecha_hora)->format('H:i')}}</span>
                            </div>
                        </div>

                        <!-- Razón (ocupa toda la fila) -->
                        <div class="flex gap-2 lg:col-span-2">
                            <div class="flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-md">
                                <i class="fa-solid fa-comment text-gray-700"></i>
                                <span class="text-sm text-gray-700">Razón del {{ $solicitud->tipo_solicitud }}:</span>
                            </div>
                            <div class="flex-1 bg-gray-100 px-3 py-2 rounded-md flex items-center">
                                <span class="text-sm text-gray-900">{{ $solicitud->razon }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="card mb-6">
                    <div class="card-header">
                        <i class="fas fa-table mr-2"></i>
                        Tabla detalle de la solicitud
                    </div>
                    <div class="card-body overflow-x-auto">
                        <table class="table table-striped">
                            <thead class="bg-blue-600">
                                <tr>
                                    <th style="color: white !important;">Producto</th>
                                    <th style="color: white !important;">Cantidad</th>
                                    <th style="color: white !important;">Precio de compra</th>
                                    <th style="color: white !important;">Subtotal</th>
                                    @if($solicitud->tipo_solicitud == 'prestamo')
                                        <th style="color: white !important;">Devuelto</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($solicitud->productos as $producto)
                                    <tr>
                                        <td>{{$producto->nombre}}</td>
                                        <td>{{$producto->pivot->cantidad}}</td>
                                        <td>{{$producto->pivot->precio_compra}}</td>
                                        <td class="td_subtotal">{{($producto->pivot->cantidad) * ($producto->pivot->precio_compra)}}</td>
                                        @if($solicitud->tipo_solicitud == 'prestamo')
                                            <td>
                                                @if($producto->pivot->fecha_devolucion)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{\Carbon\Carbon::parse($producto->pivot->fecha_devolucion)->format('d-m-Y H:i')}}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        No
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
