@extends('template')

@section('title', 'Gestión de Productos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="px-4 py-6" x-data="productManagement()">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Gestión de Productos</h1>
        <nav class="breadcrumb mb-6">
            <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
            <div class="breadcrumb-item active">Gestión de productos</div>
        </nav>

        <!-- Botón Añadir Producto -->
        <div class="flex justify-between items-center mb-6">
            <button
                @click="showAddProductModal = true"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 inline-flex items-center gap-2"
            >
                <i class="fas fa-plus"></i> Añadir Producto
            </button>
        </div>

        <!-- ===== MODALES ===== -->

        <!-- Modal Añadir Producto (z-40) -->
        <div x-show="showAddProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40 p-4" style="display: none;">
            <div @click.away="handleCloseMainModal()" class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[85vh] flex flex-col">
                <!-- Header del Modal -->
                <div class="px-6 py-4 border-b">
                    <h3 class="text-xl font-bold">Añadir Nuevo Producto</h3>
                </div>
                
                <!-- Body del Modal con Scroll -->
                <div class="px-6 py-4 overflow-y-auto flex-1">
                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Código -->
                        <div class="form-group">
                            <label for="codigo" class="block text-sm font-medium text-gray-700">Código:</label>
                            <input type="text" name="codigo" id="codigo" class="mt-1 p-2 border rounded w-full" value="{{ old('codigo') }}">
                            @error('codigo')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Nombre (OBLIGATORIO) -->
                        <div class="form-group">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">
                                Nombre: <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" class="mt-1 p-2 border rounded w-full" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 p-2 border rounded w-full">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Fecha de vencimiento -->
                        <div class="form-group">
                            <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700">Fecha de vencimiento:</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="mt-1 p-2 border rounded w-full" value="{{ old('fecha_vencimiento') }}">
                            @error('fecha_vencimiento')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Imagen -->
                        <div class="form-group">
                            <label for="img_path" class="block text-sm font-medium text-gray-700">Imagen:</label>
                            <input type="file" name="img_path" id="img_path" class="mt-1 p-2 border rounded w-full" accept="image/*">
                            @error('img_path')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tipo (OBLIGATORIO) -->
                        <div class="form-group">
                            <label for="tipo" class="block text-sm font-medium text-gray-700">
                                Tipo: <span class="text-red-500">*</span>
                            </label>
                            <select name="tipo" id="tipo" class="mt-1 p-2 border rounded w-full" required>
                                <option value="">Seleccione el tipo</option>
                                <option value="BP" {{ old('tipo') == 'BP' ? 'selected' : '' }}>BP</option>
                                <option value="Insumo" {{ old('tipo') == 'Insumo' ? 'selected' : '' }}>Insumo</option>
                            </select>
                            @error('tipo')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Marca -->
                        <div class="form-group">
                            <label for="marca_id" class="block text-sm font-medium text-gray-700">Marca:</label>
                            <div class="flex items-center gap-2">
                                <select name="marca_id" id="marca_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione una marca</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>
                                            {{ $marca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" @click.prevent="showAddBrandModal = true" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 font-bold">+</button>
                            </div>
                            @error('marca_id')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Categorías -->
                        <div class="form-group">
                            <label for="categorias" class="block text-sm font-medium text-gray-700">Categorías:</label>
                            <div class="flex items-center gap-2">
                                <select name="categorias[]" id="categorias" multiple class="mt-1 p-2 border rounded w-full" size="4">
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ in_array($categoria->id, old('categorias', [])) ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" @click.prevent="showAddCategoryModal = true" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 font-bold">+</button>
                            </div>
                            <small class="text-gray-500 text-xs">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples</small>
                            @error('categorias')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Sugerencia -->
                        <div class="form-group">
                            <label for="sugerencia" class="block text-sm font-medium text-gray-700">Sugerencia:</label>
                            <textarea name="sugerencia" id="sugerencia" rows="4" class="mt-1 p-2 border rounded w-full" placeholder="Ej: Desechar, Donar, Uso en el laboratorio">{{ old('sugerencia') }}</textarea>
                            @error('sugerencia')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Ubicaciones -->
                        <div class="form-group">
                            <label for="ubicaciones" class="block text-sm font-medium text-gray-700">Ubicaciones:</label>
                            <select name="ubicaciones[]" id="ubicaciones" multiple class="mt-1 p-2 border rounded w-full" size="4">
                                @foreach ($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->id }}" {{ in_array($ubicacion->id, old('ubicaciones', [])) ? 'selected' : '' }}>
                                        {{ $ubicacion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-gray-500 text-xs">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples</small>
                            @error('ubicaciones')
                                <small class="text-red-500 text-xs">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Footer del Modal (Fijo) -->
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-between items-center">
                    <a href="{{ route('adquisiciones.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded inline-flex items-center gap-2 text-sm">
                        <i class="fas fa-shopping-cart"></i>
                        Ir a Adquisiciones
                    </a>
                    <div class="flex gap-2">
                        <button type="button" @click="showAddProductModal = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-save"></i>
                            Guardar Producto
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <!-- Modal Añadir Marca (z-50) -->
        <div x-show="showAddBrandModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div @click.away="showAddBrandModal = false" class="bg-white rounded-lg shadow-xl p-6 max-w-md">
                <h3 class="text-xl font-bold mb-4">Añadir Nueva Marca</h3>
                <form @submit.prevent="addBrand">
                    <input type="text" x-model="formData.brand.name" placeholder="Nombre de la marca" class="p-2 border rounded w-full">
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="showAddBrandModal = false" class="text-gray-600 mr-4">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Añadir Categoría (z-50) -->
        <div x-show="showAddCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div @click.away="showAddCategoryModal = false" class="bg-white rounded-lg shadow-xl p-6 max-w-md">
                <h3 class="text-xl font-bold mb-4">Añadir Nueva Categoría</h3>
                <form @submit.prevent="addCategory">
                    <input type="text" x-model="formData.category.name" placeholder="Nombre de la categoría" class="p-2 border rounded w-full">
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="showAddCategoryModal = false" class="text-gray-600 mr-4">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Toast Notification (z-50) -->
        <div x-show="toast.visible" x-transition class="fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50" style="display: none;">
            <span x-text="toast.message"></span>
        </div>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-table mr-2"></i>
                Tabla productos ({{ $productos->count() }} registros)
            </div>
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table id="datatablesSimple" class="table table-striped">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($productos as $producto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        {{ $producto->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        {{--  
                                        @can('editar-producto')
                                            <a href="{{ route('productos.edit', ['producto' => $producto]) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i>Editar
                                            </a>
                                        @endcan
                                        --}}
                                        <button onclick="window.productModal.openViewModal({{ $producto->id }})" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye mr-1"></i>Ver
                                        </button>

                                        @can('eliminar-producto')
                                            @if ($producto->estado == 1)
                                                <button onclick="window.productModal.openConfirmModal({{ $producto->id }})" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash mr-1"></i>Eliminar
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No hay productos registrados
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
<script>
function productManagement() {
    return {
        // State
        showAddProductModal: false,
        showAddBrandModal: false,
        showAddCategoryModal: false,
        toast: { message: '', type: 'success', visible: true },

        // Data - Solo para los modales secundarios
        formData: {
            brand: { name: '' },
            category: { name: '' }
        },

        // Methods
        showToast(message, type = 'success') {
            this.toast = { message, type, visible: true };
            setTimeout(() => this.toast.visible = false, 3000);
        },
        
        handleCloseMainModal() {
            // Solo cerrar el modal principal si no hay modales secundarios abiertos
            if (!this.showAddBrandModal && !this.showAddCategoryModal) {
                this.showAddProductModal = false;
            }
        },

        addBrand() {
            if (this.formData.brand.name.trim()) {
                // Aquí podrías hacer un POST AJAX para crear la marca
                // Por ahora solo agregamos al select dinámicamente
                const newBrand = {
                    id: Date.now(), // ID temporal
                    name: this.formData.brand.name
                };
                
                // Agregar la opción al select
                const select = document.getElementById('marca_id');
                const option = document.createElement('option');
                option.value = newBrand.id;
                option.text = newBrand.name;
                option.selected = true;
                select.add(option);
                
                this.formData.brand.name = '';
                this.showAddBrandModal = false;
                this.showToast('Marca agregada');
            }
        },

        addCategory() {
            if (this.formData.category.name.trim()) {
                // Aquí podrías hacer un POST AJAX para crear la categoría
                // Por ahora solo agregamos al select dinámicamente
                const newCategory = {
                    id: Date.now(), // ID temporal
                    name: this.formData.category.name
                };
                
                // Agregar la opción al select
                const select = document.getElementById('categorias');
                const option = document.createElement('option');
                option.value = newCategory.id;
                option.text = newCategory.name;
                option.selected = true;
                select.add(option);
                
                this.formData.category.name = '';
                this.showAddCategoryModal = false;
                this.showToast('Categoría agregada');
            }
        }
    }
}
</script>

<!-- Script para reabrir el modal si hay errores de validación -->
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Esperar a que Alpine.js esté listo
            setTimeout(() => {
                // Reabrir el modal de añadir producto
                const element = document.querySelector('[x-data]');
                if (element && element.__x) {
                    element.__x.$data.showAddProductModal = true;
                }
            }, 100);
        });
    </script>
@endif

<script>
    console.log('Simple DataTables script loaded');
    window.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - checking for table');
        const table = document.getElementById('datatablesSimple');
        console.log('Table found:', table);

        if (table) {
            const tbody = table.querySelector('tbody');
            const rows = tbody ? tbody.querySelectorAll('tr') : [];
            console.log('Table rows found:', rows.length);

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
                console.log('DataTable initialized successfully', dataTable);
            } catch (error) {
                console.error('Error initializing DataTable:', error);
            }
        } else {
            console.error('Table with ID "datatablesSimple" not found');
        }
    });
</script>

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
                marca: '{{ $producto->marca && $producto->marca->caracteristica ? $producto->marca->caracteristica->nombre : "Sin marca" }}',
                descripcion: `{{ $producto->descripcion ?? '' }}`,
                fecha_vencimiento: '{{ $producto->fecha_vencimiento ?? '' }}',
                img_path: '{{ $producto->img_path ?? '' }}',
                img_url: '{{ $producto->img_path ? asset("storage/productos/" . $producto->img_path) : "" }}',
                estado: {{ $producto->estado }}
            },
            @endforeach
        },

        openViewModal(productId) {
            const product = this.products[productId];
            if (!product) return;

            // Fill modal content
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

            const message = product.estado == 1 ?
                '¿Seguro que quieres eliminar el producto?' :
                '¿Seguro que quieres restaurar el producto?';

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