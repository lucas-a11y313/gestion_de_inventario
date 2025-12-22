@extends('template')

@section('title', 'Gestión de Productos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container mx-auto p-4" x-data="productManagement()">
    

    <!-- Controles y Búsqueda -->
    <div class="flex justify-between items-center mb-4">
        
        <button
            @click="showAddProductModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
        >
            <i class="fas fa-plus mr-2"></i> Añadir Producto
        </button>
    </div>

    <!-- ===== MODALES ===== -->

    <!-- Modal Añadir Producto (z-40) -->
    <div x-show="showAddProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40" style="display: none;">
        <div @click.away="handleCloseMainModal()" class="bg-white rounded-lg shadow-xl p-6 max-w-3xl">
            <h3 class="text-xl font-bold mb-4">Añadir Nuevo Producto</h3>
            <form @submit.prevent="addProduct">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" x-model="formData.product.code" placeholder="Código del producto" class="p-2 border rounded">
                    <input type="text" x-model="formData.product.name" placeholder="Nombre del producto" class="p-2 border rounded">
                    <textarea x-model="formData.product.description" placeholder="Descripción" class="p-2 border rounded col-span-2"></textarea>
                    
                    <div class="flex items-center gap-2">
                        <select x-model="formData.product.brand" class="p-2 border rounded w-full">
                            <option value="">Selecciona una Marca</option>
                            <template x-for="brand in brands" :key="brand.id">
                                <option :value="brand.id" x-text="brand.name"></option>
                            </template>
                        </select>
                        <button @click.prevent="showAddBrandModal = true" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 font-bold">+</button>
                    </div>

                    <div class="flex items-center gap-2">
                        <select x-model="formData.product.category" class="p-2 border rounded w-full">
                            <option value="">Selecciona una Categoría</option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                        <button @click.prevent="showAddCategoryModal = true" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 font-bold">+</button>
                    </div>
                    
                    <input type="number" x-model="formData.product.stock" placeholder="Stock inicial" class="p-2 border rounded">
                    <input type="number" step="0.01" x-model="formData.product.price" placeholder="Precio" class="p-2 border rounded">
                    
                    <div class="col-span-2 flex items-center gap-2">
                        <select x-model="formData.product.supplier" class="p-2 border rounded w-full">
                            <option value="">Selecciona un Proveedor</option>
                            <template x-for="supplier in suppliers" :key="supplier.id">
                                <option :value="supplier.id" x-text="supplier.name"></option>
                            </template>
                        </select>
                        <button @click.prevent="showAddSupplierModal = true" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 font-bold">+</button>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" @click="showAddProductModal = false" class="text-gray-600 mr-4">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
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
    
    <!-- Modal Añadir Proveedor (z-50) -->
    <div x-show="showAddSupplierModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div @click.away="showAddSupplierModal = false" class="bg-white rounded-lg shadow-xl p-6 max-w-lg">
            <h3 class="text-xl font-bold mb-4">Añadir Nuevo Proveedor</h3>
            <form @submit.prevent="addSupplier">
                 <div class="grid grid-cols-1 gap-4">
                    <input type="text" x-model="formData.supplier.name" placeholder="Nombre del proveedor" class="p-2 border rounded">
                    <input type="text" x-model="formData.supplier.ruc" placeholder="RUC" class="p-2 border rounded">
                    <input type="text" x-model="formData.supplier.address" placeholder="Dirección" class="p-2 border rounded">
                    <input type="text" x-model="formData.supplier.phone" placeholder="Teléfono" class="p-2 border rounded">
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" @click="showAddSupplierModal = false" class="text-gray-600 mr-4">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification (z-50) -->
    <div x-show="toast.visible" x-transition class="fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50" style="display: none;">
        <span x-text="toast.message"></span>
    </div>
</div>

<div class="px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Gestión de Productos</h1>
        <nav class="breadcrumb mb-6">
            <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
            <div class="breadcrumb-item active">Gestión de productos</div>
        </nav>
        
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
        showAddSupplierModal: false,
        toast: { message: '', type: 'success', visible: false },

        // Data
        brands: [ { id: 1, name: 'ABB' }, { id: 2, name: 'Siemens' } ],
        categories: [ { id: 1, name: 'Transformadores' }, { id: 2, name: 'Medidores' } ],
        suppliers: [ { id: 1, name: 'Atenas Energía', ruc: '80123456789' } ],
        products: [
            { id: 1, code: 'PROD-001', name: 'Transformador 100kVA', brand: 'ABB', category: 'Transformadores', stock: 5 },
            { id: 2, code: 'PROD-002', name: 'Medidor kWh', brand: 'Siemens', category: 'Medidores', stock: 12 }
        ],
        formData: {
            product: { code: '', name: '', description: '', brand: '', category: '', stock: '', price: '', supplier: '' },
            brand: { name: '' },
            category: { name: '' },
            supplier: { name: '', ruc: '', address: '', phone: '' }
        },

        // Methods
        showToast(message, type = 'success') {
            this.toast = { message, type, visible: true };
            setTimeout(() => this.toast.visible = false, 3000);
        },
        
                        resetProductForm() {
        
                            this.formData.product = { code: '', name: '', description: '', brand: '', category: '', stock: '', price: '', supplier: '' };
        
                        },
        
                        handleCloseMainModal() {
        
                            if (!this.showAddBrandModal && !this.showAddCategoryModal && !this.showAddSupplierModal) {
        
                                this.showAddProductModal = false;
        
                                this.resetProductForm();
        
                            }
        
                        },
        
        

        addBrand() {
            if (this.formData.brand.name.trim()) {
                const newBrand = {
                    id: Math.max(...this.brands.map(b => b.id), 0) + 1,
                    name: this.formData.brand.name
                };
                this.brands.push(newBrand);
                this.$nextTick(() => {
                    this.formData.product.brand = newBrand.id;
                });
                this.formData.brand.name = '';
                this.showAddBrandModal = false;
                this.showToast('Marca agregada');
            }
        },

        addCategory() {
            if (this.formData.category.name.trim()) {
                const newCategory = {
                    id: Math.max(...this.categories.map(c => c.id), 0) + 1,
                    name: this.formData.category.name
                };
                this.categories.push(newCategory);
                this.$nextTick(() => {
                    this.formData.product.category = newCategory.id;
                });
                this.formData.category.name = '';
                this.showAddCategoryModal = false;
                this.showToast('Categoría agregada');
            }
        },
        
        addSupplier() {
            if (this.formData.supplier.name.trim() && this.formData.supplier.ruc.trim()) {
                const newSupplier = {
                    id: Math.max(...this.suppliers.map(s => s.id), 0) + 1,
                    ...this.formData.supplier
                };
                this.suppliers.push(newSupplier);
                this.$nextTick(() => {
                    this.formData.product.supplier = newSupplier.id;
                });
                this.formData.supplier = { name: '', ruc: '', address: '', phone: '' };
                this.showAddSupplierModal = false;
                this.showToast('Proveedor agregado');
            }
        },

        addProduct() {
            const { product } = this.formData;
            if (product.code && product.name && product.brand && product.category && product.stock) {
                const newProduct = {
                    id: Math.max(...this.products.map(p => p.id), 0) + 1,
                    code: product.code,
                    name: product.name,
                    description: product.description,
                    brand: this.brands.find(b => b.id == product.brand)?.name || '',
                    category: this.categories.find(c => c.id == product.category)?.name || '',
                    supplier: this.suppliers.find(s => s.id == product.supplier)?.name || '',
                    stock: parseInt(product.stock),
                    price: parseFloat(product.price)
                };
                this.products.push(newProduct);
                this.showToast('Producto agregado');
                this.showAddProductModal = false;
                this.resetProductForm();
            } else {
                this.showToast('Completa los campos requeridos', 'error');
            }
        },

        deleteProduct(id) {
            this.products = this.products.filter(p => p.id !== id);
            this.showToast('Producto eliminado');
        }
    }
}
</script>

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