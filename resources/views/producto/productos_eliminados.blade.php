@extends('template')

@section('title', 'Productos Eliminados')

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
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Productos Eliminados</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></div>
                <div class="breadcrumb-item active">Productos eliminados</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('productos.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-table mr-2"></i>Tabla productos
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-gray-700 font-medium">Tabla productos eliminados ({{ $productos->count() }} registros)</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorías</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($productos as $producto)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto->codigo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $producto->tipo === 'BP' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $producto->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $producto->marca?->caracteristica?->nombre ?? 'Sin marca' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $producto->modelo?->caracteristica?->nombre ?? 'Sin modelo' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @if($producto->categorias && $producto->categorias->count() > 0)
                                                @foreach ($producto->categorias as $categoria)
                                                    @if($categoria->caracteristica)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ $categoria->caracteristica->nombre }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-400">Sin categorías</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->ubicacion ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Eliminado
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="window.productModal.openViewModal({{ $producto->id }})" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye mr-1"></i>Ver
                                            </button>

                                            @can('eliminar-producto')
                                                <button onclick="window.productModal.openConfirmModal({{ $producto->id }})" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-undo mr-1"></i>Restaurar
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay productos eliminados
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Ver Producto -->
            <div id="viewModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.productModal.closeViewModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Detalles del producto</h3>
                                <button onclick="window.productModal.closeViewModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Código:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-codigo"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-nombre"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Marca:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-marca"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-descripcion"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha de vencimiento:</label>
                                    <p class="mt-1 text-sm text-gray-900" id="modal-fecha"></p>
                                </div>
                                <div id="modal-image-container" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700">Imagen:</label>
                                    <div class="mt-2">
                                        <img id="modal-image" class="max-w-full h-auto rounded-lg border max-h-64">
                                    </div>
                                </div>
                                <div id="modal-no-image" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700">Imagen:</label>
                                    <p class="mt-1 text-sm text-gray-500">Sin imagen</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button onclick="window.productModal.closeViewModal()" class="btn btn-secondary">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmación -->
            <div id="confirmModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="window.productModal.closeConfirmModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-undo text-blue-600"></i>
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
                                <button type="submit" class="btn btn-primary mr-2">
                                    Confirmar
                                </button>
                            </form>
                            <button onclick="window.productModal.closeConfirmModal()" class="btn btn-secondary">
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
        // Global product modal object
        window.productModal = {
            // Datos de productos desde el backend
            products: {
                @foreach ($productos as $producto)
                {{ $producto->id }}: {
                    id: {{ $producto->id }},
                    codigo: '{{ $producto->codigo }}',
                    nombre: '{{ $producto->nombre }}',
                    marca: '{{ $producto->marca?->caracteristica?->nombre ?? "Sin marca" }}',
                    descripcion: `{{ $producto->descripcion ?? '' }}`,
                    fecha_vencimiento: '{{ $producto->fecha_vencimiento ?? '' }}',
                    img_path: '{{ $producto->img_path ?? '' }}',
                    img_url: '{{ $producto->img_path ? Storage::url("productos/{$producto->img_path}") : "" }}',
                    estado: {{ $producto->estado }}
                },
                @endforeach
            },

            openViewModal(productId) {
                const product = this.products[productId];
                if (!product) return;

                // Fill modal content
                document.getElementById('modal-codigo').textContent = product.codigo;
                document.getElementById('modal-nombre').textContent = product.nombre;
                document.getElementById('modal-marca').textContent = product.marca;
                document.getElementById('modal-descripcion').textContent = product.descripcion || 'Sin descripción';
                document.getElementById('modal-fecha').textContent = product.fecha_vencimiento || 'No tiene';

                // Handle image
                if (product.img_path && product.img_url) {
                    document.getElementById('modal-image').src = product.img_url;
                    document.getElementById('modal-image').alt = product.nombre;
                    document.getElementById('modal-image-container').style.display = 'block';
                    document.getElementById('modal-no-image').style.display = 'none';
                } else {
                    document.getElementById('modal-image-container').style.display = 'none';
                    document.getElementById('modal-no-image').style.display = 'block';
                }

                // Show modal
                document.getElementById('viewModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            },

            closeViewModal() {
                document.getElementById('viewModal').style.display = 'none';
                document.body.style.overflow = '';
            },

            openConfirmModal(productId) {
                const product = this.products[productId];
                if (!product) return;

                const message = '¿Seguro que quieres restaurar el producto?';

                document.getElementById('confirm-message').textContent = message;
                document.getElementById('confirm-form').action = `/productos/${productId}`;

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
