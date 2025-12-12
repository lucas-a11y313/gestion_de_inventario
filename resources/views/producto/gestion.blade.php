@extends('template')

@section('title', 'Gestión de Productos')

@section('content')
<div class="container mx-auto p-4" x-data="productManagement()">
    <h1 class="text-3xl font-bold mb-4">Gestión de Productos</h1>

    <!-- Controles y Búsqueda -->
    <div class="flex justify-between items-center mb-4">
        <div class="relative w-1/3">
            <input
                type="text"
                x-model="searchTerm"
                placeholder="Buscar producto..."
                class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
        <button
            @click="showAddProductModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
        >
            <i class="fas fa-plus mr-2"></i> Añadir Producto
        </button>
    </div>

    <!-- Tabla de Productos -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Código</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Marca</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Categoría</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="product in filteredProducts" :key="product.id">
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm" x-text="product.code"></td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm" x-text="product.name"></td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm" x-text="product.brand"></td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm" x-text="product.category"></td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm" x-text="product.stock"></td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </template>
                 <template x-if="products.length === 0">
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500">
                            No hay productos para mostrar.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
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
@endsection

@push('js')
<script>
function productManagement() {
    return {
        // State
        searchTerm: '',
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

        // Computed
        get filteredProducts() {
            if (!this.searchTerm) return this.products;
            const term = this.searchTerm.toLowerCase();
            return this.products.filter(p => 
                Object.values(p).some(value => 
                    String(value).toLowerCase().includes(term)
                )
            );
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
@endpush