@extends('template')

@section('title', 'Productos')

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

    <div class="px-4 py-6" x-data="productsModal()">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Productos</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Productos</div>
            </nav>

            @can('crear-producto')
                <div class="flex flex-col lg:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <a href="{{ route('productos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Añadir nuevo registro
                        </a>
                    </div>

                    <div>
                        <a href="{{ route('productos.inventario.pdf') }}" target="_blank" class="btn btn-success">
                            <i class="fas fa-trash-restore mr-2"></i>
                            Insumos eliminados
                        </a>
                    </div>
                </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Tabla productos
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorías</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sugerencia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productos as $producto)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto->codigo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->marca->caracteristica->nombre }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($producto->categorias as $categoria)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $categoria->caracteristica->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($producto->estado == 1)
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
                                            @can('editar-producto')
                                                <a href="{{ route('productos.edit', ['producto' => $producto]) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit mr-1"></i>Editar
                                                </a>
                                            @endcan

                                            <button @click="openViewModal({{ $producto->id }})" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye mr-1"></i>Ver
                                            </button>

                                            @can('eliminar-producto')
                                                @if ($producto->estado == 1)
                                                    <button @click="openConfirmModal({{ $producto->id }})" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                                    </button>
                                                @else
                                                    <button @click="openConfirmModal({{ $producto->id }})" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-undo mr-1"></i>Restaurar
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

            <!-- Modal Ver Producto -->
            <div x-show="showViewModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[60] overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeViewModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Detalles del producto</h3>
                                <button @click="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Código:</label>
                                    <p class="mt-1 text-sm text-gray-900" x-text="selectedProduct?.codigo"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre:</label>
                                    <p class="mt-1 text-sm text-gray-900" x-text="selectedProduct?.nombre"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Marca:</label>
                                    <p class="mt-1 text-sm text-gray-900" x-text="selectedProduct?.marca"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                                    <p class="mt-1 text-sm text-gray-900" x-text="selectedProduct?.descripcion || 'Sin descripción'"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha de vencimiento:</label>
                                    <p class="mt-1 text-sm text-gray-900" x-text="selectedProduct?.fecha_vencimiento || 'No tiene'"></p>
                                </div>
                                <div x-show="selectedProduct?.img_path">
                                    <label class="block text-sm font-medium text-gray-700">Imagen:</label>
                                    <div class="mt-2">
                                        <img :src="selectedProduct?.img_url"
                                             :alt="selectedProduct?.nombre"
                                             class="max-w-full h-auto rounded-lg border max-h-64">
                                    </div>
                                </div>
                                <div x-show="!selectedProduct?.img_path">
                                    <label class="block text-sm font-medium text-gray-700">Imagen:</label>
                                    <p class="mt-1 text-sm text-gray-500">Sin imagen</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="closeViewModal()" class="btn btn-secondary">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmación -->
            <div x-show="showConfirmModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[60] overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeConfirmModal()"></div>

                    <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium text-gray-900">Mensaje de confirmación</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-text="confirmMessage"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <form :action="confirmAction" method="post" class="inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger mr-2">
                                    Confirmar
                                </button>
                            </form>
                            <button @click="closeConfirmModal()" class="btn btn-secondary">
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
        function productsModal() {
            return {
                showViewModal: false,
                showConfirmModal: false,
                selectedProduct: null,
                confirmMessage: '',
                confirmAction: '',

                // Datos de productos desde el backend
                products: {
                    @foreach ($productos as $producto)
                    {{ $producto->id }}: {
                        id: {{ $producto->id }},
                        codigo: '{{ $producto->codigo }}',
                        nombre: '{{ $producto->nombre }}',
                        marca: '{{ $producto->marca->caracteristica->nombre }}',
                        descripcion: `{{ $producto->descripcion ?? '' }}`,
                        fecha_vencimiento: '{{ $producto->fecha_vencimiento ?? '' }}',
                        img_path: '{{ $producto->img_path ?? '' }}',
                        img_url: '{{ $producto->img_path ? Storage::url("productos/" . $producto->img_path) : "" }}',
                        estado: {{ $producto->estado }}
                    },
                    @endforeach
                },

                openViewModal(productId) {
                    this.selectedProduct = this.products[productId];
                    this.showViewModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeViewModal() {
                    this.showViewModal = false;
                    this.selectedProduct = null;
                    document.body.style.overflow = '';
                },

                openConfirmModal(productId) {
                    const product = this.products[productId];
                    this.selectedProduct = product;
                    this.confirmMessage = product.estado == 1 ?
                        '¿Seguro que quieres eliminar el producto?' :
                        '¿Seguro que quieres restaurar el producto?';
                    this.confirmAction = `/productos/${productId}`;
                    this.showConfirmModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeConfirmModal() {
                    this.showConfirmModal = false;
                    this.selectedProduct = null;
                    this.confirmMessage = '';
                    this.confirmAction = '';
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
@endpush
