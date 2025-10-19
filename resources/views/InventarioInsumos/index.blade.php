@extends('template')

@section('title', 'Inventario de Insumos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Inventario de Insumos</h1>

            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Inventario Insumos</div>
            </nav>

            @can('crear-producto')
                <!-- Buttons Section -->
                <div class="flex flex-wrap gap-4 mb-6">
                    <a href="{{ route('insumos.inventario.pdf') }}" target="_blank" class="btn btn-success">
                        <i class="fas fa-file-pdf"></i>
                        Generar informe completo
                    </a>
                    <a href="{{ route('insumos.origen') }}" class="btn btn-warning">
                        <i class="fas fa-truck-loading"></i>
                        Origen de insumos
                    </a>
                    <a href="{{ route('insumos.retirados') }}" class="btn btn-danger">
                        <i class="fas fa-hand-holding"></i>
                        Insumos retirados
                    </a>
                    <a href="{{ route('insumos.prestados') }}" class="btn btn-primary">
                        <i class="fas fa-handshake"></i>
                        Insumos prestados
                    </a>
                </div>
            @endcan
            
            <!-- Table Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-boxes mr-2"></i>
                    Tabla Inventario Insumos
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código del Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($insumos as $insumo)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $insumo->codigo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $insumo->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                {{ $insumo->stock }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @php
                                                // Get prices from adquisiciones
                                                $preciosAdquisiciones = $insumo->adquisiciones->pluck('pivot.precio_compra')->filter();
                                                $precioPromedio = $preciosAdquisiciones->isNotEmpty() ? $preciosAdquisiciones->avg() : 0;
                                            @endphp
                                            {{ number_format($precioPromedio, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="window.insumoModal.openViewModal({{ $insumo->id }})" class="btn btn-success btn-sm">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay insumos registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Ver Insumo -->
            <div id="viewModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.insumoModal.closeViewModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Detalles del insumo</h3>
                                <button onclick="window.insumoModal.closeViewModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div id="modal-image-container" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700">Imagen del producto:</label>
                                    <div class="mt-2">
                                        <img id="modal-image" class="max-w-full h-auto rounded-lg border max-h-96">
                                    </div>
                                </div>
                                <div id="modal-no-image" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700">Imagen del producto:</label>
                                    <p class="mt-1 text-sm text-gray-500">Sin imagen</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button onclick="window.insumoModal.closeViewModal()" class="btn btn-secondary">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmación -->
            <div id="confirmModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.insumoModal.closeConfirmModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" id="confirm-message"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <form id="confirm-form" method="post" class="inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger mr-2">
                                    Confirmar
                                </button>
                            </form>
                            <button onclick="window.insumoModal.closeConfirmModal()" class="btn btn-secondary">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

    <script>
        // Global insumo modal object
        window.insumoModal = {
            async openViewModal(insumoId) {
                try {
                    // Fetch data from backend
                    const response = await fetch(`/inventarioinsumos/${insumoId}/data`);
                    if (!response.ok) {
                        throw new Error('Error al cargar los datos');
                    }

                    const insumo = await response.json();

                    // Handle image
                    if (insumo.img_path && insumo.img_url) {
                        document.getElementById('modal-image').src = insumo.img_url;
                        document.getElementById('modal-image').alt = insumo.nombre;
                        document.getElementById('modal-image-container').style.display = 'block';
                        document.getElementById('modal-no-image').style.display = 'none';
                    } else {
                        document.getElementById('modal-image-container').style.display = 'none';
                        document.getElementById('modal-no-image').style.display = 'block';
                    }

                    // Show modal
                    document.getElementById('viewModal').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del insumo');
                }
            },

            closeViewModal() {
                document.getElementById('viewModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            openConfirmModal(insumoId) {
                const message = '¿Seguro que quieres eliminar este insumo?';

                document.getElementById('confirm-message').textContent = message;
                document.getElementById('confirm-form').action = `/inventarioinsumos/${insumoId}`;

                document.getElementById('confirmModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeConfirmModal() {
                document.getElementById('confirmModal').style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    </script>
@endpush
