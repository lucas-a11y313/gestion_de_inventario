@extends('template')

@section('title', 'Inventario de BP')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Inventario de BP</h1>

            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="#">Inicio</a></div>
                <div class="breadcrumb-item active">Inventario de BP</div>
            </nav>

            <div class="flex flex-col lg:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Añadir nuevo registro
                    </a>
                </div>

                <div>
                    <a href="{{ route('productos.inventario.pdf') }}" target="_blank" class="btn btn-success">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Generar informe del inventario
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-warehouse mr-2"></i>
                    Tabla Inventario
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código del producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propietario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Ejemplo de filas estáticas solo para mockup --}}
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BP-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PRD-123</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Notebook Dell</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Raj Kumar</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-gray-800 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-700">Negro</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button type="button" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i>Editar
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i>Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BP-002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PRD-456</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Proyector Epson</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sergio Morel</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-white border-2 border-gray-300 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-700">Blanco</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button type="button" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i>Editar
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i>Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">BP-003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PRD-789</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Monitor LG 24"</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Jorge Zarza</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 bg-gray-900 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-700">Negro</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button type="button" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i>Editar
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i>Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
