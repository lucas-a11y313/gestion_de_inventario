@extends('template')

@section('title','Ver proyecto')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
    <div class="px-4 py-6" style="padding-top: 2rem;">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6" style="margin-top: 20px;">Ver Proyecto</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('proyectos.index') }}">Proyectos</a></div>
                <div class="breadcrumb-item active">Ver proyecto</div>
            </nav>

            <!-- Encabezado del proyecto -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-black">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Información del proyecto -->
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-bold mb-4">{{ $proyecto->nombre }}</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($proyecto->fecha_inicio)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-start"></i>
                                <div>
                                    <p class="text-xs text-purple-200">Fecha de inicio</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($proyecto->fecha_fin)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-check"></i>
                                <div>
                                    <p class="text-xs text-purple-200">Fecha de fin</p>
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-center gap-2">
                                <i class="fas fa-box"></i>
                                <div>
                                    <p class="text-xs text-purple-200">Total de productos</p>
                                    <p class="font-semibold">{{ $proyecto->productos->count() }} {{ $proyecto->productos->count() == 1 ? 'producto' : 'productos' }}</p>
                                </div>
                            </div>

                            @if($proyecto->fecha_inicio && $proyecto->fecha_fin)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-hourglass-half"></i>
                                <div>
                                    <p class="text-xs text-purple-200">Duración estimada</p>
                                    @php
                                        $inicio = \Carbon\Carbon::parse($proyecto->fecha_inicio);
                                        $fin = \Carbon\Carbon::parse($proyecto->fecha_fin);
                                        $dias = $inicio->diffInDays($fin);
                                    @endphp
                                    <p class="font-semibold">{{ $dias }} {{ $dias == 1 ? 'día' : 'días' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Imagen del proyecto -->
                    @if($proyecto->imagen)
                    <div class="flex items-center justify-center">
                        <div class="bg-white rounded-lg p-2 shadow-md">
                            <img src="{{ Storage::url('public/proyectos/'.$proyecto->imagen) }}" alt="Imagen del proyecto" class="max-w-full h-48 object-cover rounded">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Descripción del proyecto -->
            @if($proyecto->descripcion)
            <div class="card mb-6">
                <div class="card-header bg-purple-600 text-white">
                    <i class="fas fa-align-left mr-2"></i>
                    Descripción del Proyecto
                </div>
                <div class="card-body">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $proyecto->descripcion }}</p>
                </div>
            </div>
            @endif

            <!-- Tabla de productos -->
            <div class="card mb-6">
                <div class="card-header bg-indigo-600 text-white">
                    <i class="fas fa-list mr-2"></i>
                    Productos del Proyecto
                </div>
                <div class="card-body overflow-x-auto">
                    @if($proyecto->productos->count() > 0)
                        <table class="table table-striped">
                            <thead class="bg-indigo-600">
                                <tr>
                                    <th class="text-white">#</th>
                                    <th class="text-white">Código</th>
                                    <th class="text-white">Producto</th>
                                    <th class="text-white">Cantidad Requerida</th>
                                    <th class="text-white">Stock Disponible</th>
                                    <th class="text-white">Valor Unitario</th>
                                    <th class="text-white">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proyecto->productos as $index => $producto)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $producto->codigo }}</td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->pivot->cantidad }}</td>
                                        <td>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($producto->stock >= $producto->pivot->cantidad) bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $producto->stock }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($producto->precio_unitario, 2) }}</td>
                                        <td>{{ number_format($producto->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <th colspan="3" class="text-right">Total de productos diferentes:</th>
                                    <th colspan="4">{{ $proyecto->productos->count() }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Cantidad total de items:</th>
                                    <th colspan="4" id="th_total_cantidad">{{ $proyecto->productos->sum('pivot.cantidad') }}</th>
                                </tr>
                                <tr class="bg-indigo-100">
                                    <th colspan="6" class="text-right text-lg">TOTAL DEL PROYECTO:</th>
                                    <th class="text-lg">{{ number_format($total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500">No hay productos asignados a este proyecto</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex gap-3 justify-center">
                <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
                @can('editar-proyecto')
                <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Proyecto
                </a>
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            console.log('Vista de proyecto cargada');
        });
    </script>
@endpush
