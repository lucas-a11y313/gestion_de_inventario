@extends('template')

@section('title', 'Proveedores')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}";
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
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Proveedores</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Proveedores</div>
            </nav>

            @can('crear-proveedor')
                <div class="mb-6 flex gap-4">
                    <a href="{{ route('proveedores.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Añadir nuevo registro
                    </a>

                    <a href="{{ route('proveedores.eliminados') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-archive"></i>
                        Proveedores eliminados
                    </a>
                </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Tabla proveedores ({{ $proveedores->count() }} registros)
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de persona</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($proveedores as $proveedore)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $proveedore->persona->razon_social }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $proveedore->persona->direccion }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-semibold text-gray-900">{{ $proveedore->persona->documento->tipo_documento }}</p>
                                            <p class="text-sm text-gray-500">{{ $proveedore->persona->numero_documento }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($proveedore->persona->tipo_persona) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($proveedore->persona->estado == 1)
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
                                                @can('editar-proveedor')
                                                    <a href="{{ route('proveedores.edit', ['proveedore' => $proveedore]) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit mr-1"></i>Editar
                                                    </a>
                                                @endcan

                                                @can('eliminar-proveedor')
                                                    @if ($proveedore->persona->estado == 1)
                                                        <button onclick="window.proveedorModal.openConfirmModal({{ $proveedore->id }})" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash mr-1"></i>Eliminar
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay proveedores registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmación -->
            <div id="confirmModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.proveedorModal.closeConfirmModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">¿Estás seguro de que quieres eliminar el proveedor?</p>
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
                            <button onclick="window.proveedorModal.closeConfirmModal()" class="btn btn-secondary">
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
                            noRows: "No se encontraron registros"
                        }
                    });
                } catch (error) {
                    console.error('Error initializing DataTable:', error);
                }
            }
        });
    </script>

    <script>
        // Global proveedor modal object
        window.proveedorModal = {
            openConfirmModal(proveedorId) {
                document.getElementById('confirm-form').action = `/proveedores/${proveedorId}`;
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
