@extends('template')

@section('title', 'Solicitudes')

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

            // Limpiar sessionStorage después de una solicitud exitosa
            if (message === "Solicitud exitosa") {
                sessionStorage.removeItem('solicitudProductos');
            }
        </script>
    @endif

    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Solicitudes</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Solicitudes</div>
            </nav>

            @can('crear-solicitud')
                <div class="flex gap-4 mb-6">
                    <a href="{{ route('solicitudes.create') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Añadir nuevo registro
                    </a>

                    <a href="{{ route('solicitudes.eliminadas') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md font-medium transition-colors inline-flex items-center gap-2">
                        <i class="fas fa-archive"></i>
                        Solicitudes eliminadas
                    </a>
                </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Tabla solicitudes ({{ $solicitudes->count() }} registros)
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de solicitud</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($solicitudes as $solicitud)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-semibold text-gray-900">{{ $solicitud->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $solicitud->user->email }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $solicitud->tipo_solicitud == 'retiro' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($solicitud->tipo_solicitud) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($solicitud->fecha_hora)->format('d-m-Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @can('mostrar-solicitud')
                                                    <a href="{{ route('solicitudes.show', ['solicitude' => $solicitud]) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye mr-1"></i>Ver
                                                    </a>
                                                @endcan

                                                @can('eliminar-solicitud')
                                                    <button onclick="window.solicitudModal.openConfirmModal({{ $solicitud->id }})" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay solicitudes registradas
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
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.solicitudModal.closeConfirmModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">¿Estás seguro de que quieres eliminar la solicitud?</p>
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
                            <button onclick="window.solicitudModal.closeConfirmModal()" class="btn btn-secondary">
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
        // Global solicitud modal object
        window.solicitudModal = {
            openConfirmModal(solicitudId) {
                document.getElementById('confirm-form').action = `/solicitudes/${solicitudId}`;
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
