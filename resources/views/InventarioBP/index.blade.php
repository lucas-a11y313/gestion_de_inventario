@extends('template')

@section('title', 'Inventario de BP')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}"
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        </script>
    @endif

    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Inventario de BP</h1>

            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Inventario de BP</div>
            </nav>

            <div class="flex flex-col lg:flex-row gap-4 mb-6">
                <div class="flex gap-4">
                    @can('crear-inventarioBP')
                        <a href="{{ route('inventariobp.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Añadir nuevo registro
                        </a>
                    @endcan

                    {{-- <button class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-archive"></i>
                        BP eliminados
                    </button> --}}
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('inventariobp.pdf') }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Generar informe completo
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($inventarioBPs as $bp)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bp->bp }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bp->producto?->codigo ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bp->producto?->nombre ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bp->user?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bp->origen }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bp->producto?->ubicacion ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @can('mostrar-inventarioBP')
                                                    <button onclick="window.bpModal.openViewModal({{ $bp->id }})" class="btn btn-success btn-sm" title="Ver imagen">
                                                        <i class="fas fa-eye mr-1"></i>Ver
                                                    </button>
                                                    <a href="{{ route('inventariobp.show', $bp->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors" title="Ver detalle e historial">
                                                        <i class="fas fa-info-circle mr-1"></i>Detalle
                                                    </a>
                                                @endcan
                                                @can('editar-inventarioBP')
                                                    <a href="{{ route('inventariobp.edit', ['inventariobp' => $bp->id]) }}" class="btn btn-warning btn-sm" title="Editar BP">
                                                        <i class="fas fa-edit mr-1"></i>Editar
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay registros de BP aún. <a href="{{ route('inventariobp.create') }}" class="text-blue-600 hover:text-blue-800">Crear el primero</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Ver BP -->
            <div id="viewModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.bpModal.closeViewModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Detalles del BP</h3>
                                <button onclick="window.bpModal.closeViewModal()" class="text-gray-400 hover:text-gray-600">
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
                            <button onclick="window.bpModal.closeViewModal()" class="btn btn-secondary">
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
        // Global BP modal object
        window.bpModal = {
            async openViewModal(itemId) {
                try {
                    // Fetch data from backend
                    const response = await fetch(`/inventariobp/${itemId}/data`);
                    if (!response.ok) {
                        throw new Error('Error al cargar los datos');
                    }

                    const item = await response.json();

                    // Handle image
                    if (item.img_path && item.img_url) {
                        document.getElementById('modal-image').src = item.img_url;
                        document.getElementById('modal-image').alt = item.nombre;
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
                    alert('Error al cargar los datos del BP');
                }
            },

            closeViewModal() {
                document.getElementById('viewModal').style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    </script>
@endpush
