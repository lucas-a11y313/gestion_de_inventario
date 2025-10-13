@extends('template')

@section('title', 'Origen de Insumos')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Origen de Insumos</h1>

            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('inventarioinsumos.index') }}">Inventario Insumos</a></div>
                <div class="breadcrumb-item active">Origen de Insumos</div>
            </nav>

            <!-- Buttons Section -->
            <div class="flex flex-wrap gap-4 mb-6">
                <a href="{{ route('inventarioinsumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Volver al inventario
                </a>
            </div>

            <!-- Table Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-truck-loading mr-2"></i>
                    Tabla Origen de Insumos ({{ $origenes->count() }} registros)
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Adquirida</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Compra</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($origenes as $origen)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $origen['producto_codigo'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $origen['producto_nombre'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                {{ $origen['cantidad'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $tipoAdquisicion = strtolower($origen['tipo_adquisicion'] ?? 'desconocido');
                                                $badgeColors = [
                                                    'proyecto' => 'bg-blue-100 text-blue-800',
                                                    'donacion' => 'bg-green-100 text-green-800',
                                                    'donación' => 'bg-green-100 text-green-800',
                                                    'convenio' => 'bg-purple-100 text-purple-800',
                                                    'prestamo' => 'bg-yellow-100 text-yellow-800',
                                                    'préstamo' => 'bg-yellow-100 text-yellow-800',
                                                    'compra' => 'bg-indigo-100 text-indigo-800',
                                                ];
                                                $badgeClass = $badgeColors[$tipoAdquisicion] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ ucfirst($origen['tipo_adquisicion'] ?? 'Desconocido') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $origen['proveedor'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($origen['precio_compra'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $origen['fecha'] ? \Carbon\Carbon::parse($origen['fecha'])->format('d-m-Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay registros de origen de insumos
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('datatablesSimple');

            if (table) {
                try {
                    const dataTable = new simpleDatatables.DataTable(table, {
                        searchable: true,
                        sortable: true,
                        paging: true,
                        perPage: 10,
                        labels: {
                            placeholder: "Buscar...",
                            noRows: "No se encontraron registros",
                            perPage: "registros por página",
                            info: "Mostrando {start} a {end} de {rows} registros"
                        }
                    });
                } catch (error) {
                    console.error('Error initializing DataTable:', error);
                }
            }
        });
    </script>
@endpush