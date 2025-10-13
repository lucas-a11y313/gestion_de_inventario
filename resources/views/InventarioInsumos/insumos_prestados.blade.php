@extends('template')

@section('title', 'Insumos Prestados')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Insumos Prestados</h1>

            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="#">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('inventarioinsumos.index') }}">Inventario Insumos</a></div>
                <div class="breadcrumb-item active">Insumos Prestados</div>
            </nav>

            <!-- Buttons Section -->
            <div class="flex flex-col lg:flex-row gap-4 mb-6">
                <div class="flex gap-4">
                    <a href="{{ route('inventarioinsumos.index') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Volver al inventario
                    </a>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-handshake mr-2"></i>
                    Tabla Insumos Prestados
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestado por</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razón del Préstamo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Prestada</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Fila 1 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INS001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Insumo Médico A</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Hospital Central</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Emergencia médica</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">15</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20/03/2024 10:00</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-undo mr-1"></i>Devolver
                                        </button>
                                    </td>
                                </tr>

                                <!-- Fila 2 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INS002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Insumo Quirúrgico B</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Clínica San José</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Campaña de vacunación</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">25</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18/03/2024 14:20</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-undo mr-1"></i>Devolver
                                        </button>
                                    </td>
                                </tr>

                                <!-- Fila 3 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INS003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Material de Curación C</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Centro de Salud Norte</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jornada de salud comunitaria</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">10</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">12/03/2024 08:30</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="btn btn-success btn-sm">
                                            <i class="fas fa-undo mr-1"></i>Devolver
                                        </button>
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
