@extends('template')

@section('title', 'Marcas')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    {{-- hay problema con este estilo de css, por eso sale todo encimado en la vista la tabla --}}
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
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Marcas</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Marcas</div>
            </nav>

            @can('crear-marca')
                <div class="flex flex-col lg:flex-row gap-4 mb-6">
                    <div class="flex gap-4">
                        <a href="{{ route('marcas.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Añadir nuevo registro
                        </a>

                        <a href="{{ route('marcas.eliminadas') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-archive"></i>
                            Marcas eliminadas
                        </a>
                    </div>
                </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Tabla marcas
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($marcas as $marca)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $marca->caracteristica->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $marca->caracteristica->descripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($marca->caracteristica->estado == 1)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Eliminado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @can('editar-marca')
                                                <a href="{{ route('marcas.edit', ['marca' => $marca]) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit mr-1"></i>Editar
                                                </a>
                                            @endcan

                                            @can('eliminar-marca')
                                                @if ($marca->caracteristica->estado == 1)
                                                    <button onclick="window.marcaModal.openConfirmModal({{ $marca->id }})" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Ver Marca -->
            <div id="viewModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.marcaModal.closeViewModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Detalles de la marca</h3>
                                <button onclick="window.marcaModal.closeViewModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-nombre"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-descripcion"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado:</label>
                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" id="modal-estado">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button onclick="window.marcaModal.closeViewModal()" class="btn btn-secondary">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmación -->
            <div id="confirmModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.marcaModal.closeConfirmModal()"></div>

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
                            <button onclick="window.marcaModal.closeConfirmModal()" class="btn btn-secondary">
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
        // Global marca modal object
        window.marcaModal = {
            // Datos de marcas desde el backend
            marcas: {
                @foreach ($marcas as $marca)
                {{ $marca->id }}: {
                    id: {{ $marca->id }},
                    nombre: '{{ $marca->caracteristica->nombre }}',
                    descripcion: '{{ $marca->caracteristica->descripcion ?? '' }}',
                    estado: {{ $marca->caracteristica->estado }}
                },
                @endforeach
            },

            openViewModal(marcaId) {
                const marca = this.marcas[marcaId];
                if (!marca) return;

                // Fill modal content
                document.getElementById('modal-nombre').textContent = marca.nombre;
                document.getElementById('modal-descripcion').textContent = marca.descripcion || 'Sin descripción';

                // Handle estado
                const estadoEl = document.getElementById('modal-estado');
                if (marca.estado == 1) {
                    estadoEl.textContent = 'Activo';
                    estadoEl.className = 'mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                } else {
                    estadoEl.textContent = 'Eliminado';
                    estadoEl.className = 'mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                }

                // Show modal
                document.getElementById('viewModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeViewModal() {
                document.getElementById('viewModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            openConfirmModal(marcaId) {
                const marca = this.marcas[marcaId];
                if (!marca) return;

                const message = marca.estado == 1
                    ? '¿Seguro que quieres eliminar la marca?'
                    : '¿Seguro que quieres restaurar la marca?';

                document.getElementById('confirm-message').textContent = message;
                document.getElementById('confirm-form').action = `/marcas/${marcaId}`;

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
