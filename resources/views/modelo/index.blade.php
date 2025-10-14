@extends('template')

@section('title','Modelos')

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
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Modelos</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Modelos</div>
            </nav>

            @can('crear-modelo')
                <div class="flex flex-col lg:flex-row gap-4 mb-6">
                    <div class="flex gap-4">
                        <a href="{{ route('modelos.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Añadir nuevo registro
                        </a>
                        <a href="{{ route('modelos.eliminados') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-archive"></i>
                            Modelos eliminados
                        </a>
                    </div>
                </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Tabla modelos
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
                            @foreach ($modelos as $modelo)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $modelo->caracteristica->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $modelo->caracteristica->descripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($modelo->caracteristica->estado == 1)
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
                                            @can('editar-modelo')
                                                <a href="{{ route('modelos.edit', ['modelo' => $modelo]) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit mr-1"></i>Editar
                                                </a>
                                            @endcan

                                            @can('eliminar-modelo')
                                                @if ($modelo->caracteristica->estado == 1)
                                                    <button onclick="window.modeloModal.openConfirmModal({{ $modelo->id }}, 'eliminar')" class="btn btn-danger btn-sm">
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

            <!-- Modal Confirmacion -->
            <div id="confirmModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.modeloModal.closeConfirmModal()"></div>

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
                            <button onclick="window.modeloModal.closeConfirmModal()" class="btn btn-secondary">
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
        // Global modelo modal object
        window.modeloModal = {
            // Datos de modelos desde el backend
            modelos: {
                @foreach ($modelos as $modelo)
                {{ $modelo->id }}: {
                    id: {{ $modelo->id }},
                    nombre: '{{ $modelo->caracteristica->nombre }}',
                    descripcion: '{{ $modelo->caracteristica->descripcion }}',
                    estado: {{ $modelo->caracteristica->estado }}
                },
                @endforeach
            },

            openConfirmModal(modeloId, action) {
                const modelo = this.modelos[modeloId];
                if (!modelo) return;

                const message = action === 'eliminar'
                    ? '¿Seguro que quieres eliminar el modelo?'
                    : '¿Seguro que quieres restaurar el modelo?';

                document.getElementById('confirm-message').textContent = message;
                document.getElementById('confirm-form').action = `/modelos/${modeloId}`;

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
