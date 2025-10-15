@php
    use App\Models\Cliente;
    use App\Models\Categoria;
    use App\Models\Compra;
    use App\Models\Marca;
    use App\Models\Modelo;
    use App\Models\Producto;
    use App\Models\Proveedore;
    use App\Models\User;
    use App\Models\Venta;
    use Spatie\Permission\Models\Role;
@endphp

@extends('template')

@section('title','Panel')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    @if (session('success'))
    <script>
        let message = "{{ session('success') }}"
        Swal.fire({
            title: message,
            showClass: {
                popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                `
            },
            hideClass: {
                popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                `
            }
        });
    </script>
    @endif

    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900">Panel de Control</h1>
            </div>

            <!-- Modules Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Módulos del Sistema</h2>
            </div>

            <!-- Module Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- <!-- Funcionarios -->
                @can('ver-cliente')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-xl text-blue-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Funcionarios</h3>
                                <span class="text-lg font-bold text-blue-600">{{ count(Cliente::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestión de funcionarios y personal del sistema</p>
                        </div>
                    </div>
                </div>
                @endcan --}}

                <!-- Productos -->
                @can('ver-producto')
                <a href="{{ route('productos.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cube text-xl text-teal-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                                <span class="text-lg font-bold text-teal-600">{{ count(Producto::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Administración completa del catálogo de productos</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Categorías -->
                @can('ver-categoria')
                <a href="{{ route('categorias.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-tag text-xl text-purple-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Categorías</h3>
                                <span class="text-lg font-bold text-purple-600">{{ count(Categoria::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Organización y clasificación de productos por categorías</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Marcas -->
                @can('ver-marca')
                <a href="{{ route('marcas.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bookmark text-xl text-orange-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Marcas</h3>
                                <span class="text-lg font-bold text-orange-600">{{ count(Marca::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestión de marcas y fabricantes de productos</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Modelos -->
                @can('ver-modelo')
                <a href="{{ route('modelos.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cog text-xl text-cyan-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Modelos</h3>
                                <span class="text-lg font-bold text-cyan-600">{{ count(Modelo::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Control de modelos y variantes de productos</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Proveedores -->
                @can('ver-proveedor')
                <a href="{{ route('proveedores.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-truck text-xl text-indigo-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Proveedores</h3>
                                <span class="text-lg font-bold text-indigo-600">{{ Proveedore::count() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestión de proveedores y contactos de suministro</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Adquisiciones -->
                @can('ver-adquisicion')
                <a href="{{ route('adquisiciones.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shopping-cart text-xl text-blue-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Adquisiciones</h3>
                                <span class="text-lg font-bold text-blue-600">{{ \App\Models\Adquisicion::count() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Registro de adquisiciones de productos</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Solicitudes -->
                @can('ver-solicitud')
                <a href="{{ route('solicitudes.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clipboard-list text-xl text-yellow-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Solicitudes</h3>
                                <span class="text-lg font-bold text-yellow-600">{{ \App\Models\Solicitud::count() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestión de solicitudes de retiro y préstamo</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Proyectos -->
                @can('ver-proyecto')
                <a href="{{ route('proyectos.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-project-diagram text-xl text-green-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Proyectos</h3>
                                <span class="text-lg font-bold text-green-600">{{ \App\Models\Proyecto::count() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Administración de proyectos y asignación de recursos</p>
                        </div>
                    </div>
                </a>
                @endcan

                {{--<!-- Clientes -->
                @can('ver-cliente')
                <a href="{{ route('clientes.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-lime-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-friends text-xl text-lime-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Clientes</h3>
                                <span class="text-lg font-bold text-lime-600">{{ count(Cliente::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestión de clientes y contactos comerciales</p>
                        </div>
                    </div>
                </a>
                @endcan--}}

                <!-- Inventario de BP -->
                @can('ver-inventarioBP')
                <a href="{{ route('inventariobp.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-database text-xl text-orange-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Inventario de BP</h3>
                                <span class="text-lg font-bold text-orange-600">{{ \App\Models\InventarioBP::count() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestiona el inventario de productos BP con códigos, nombres y cantidades</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Inventario de Insumos -->
                <a href="{{ route('inventarioinsumos.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cube text-xl text-teal-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Inventario de Insumos</h3>
                                <span class="text-lg font-bold text-teal-600">{{ 852 }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Control completo de insumos con estados, retiros y razones</p>
                        </div>
                    </div>
                </a>

                <!-- Usuarios -->
                @can('ver-user')
                <a href="{{ route('users.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-xl text-pink-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Usuarios</h3>
                                <span class="text-lg font-bold text-pink-600">{{ count(User::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Administración de usuarios del sistema</p>
                        </div>
                    </div>
                </a>
                @endcan

                <!-- Roles -->
                @can('ver-role')
                <a href="{{ route('roles.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300 block">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-xl text-red-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-lg font-semibold text-gray-900">Roles</h3>
                                <span class="text-lg font-bold text-red-600">{{ count(Role::all()) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">Gestión de roles y permisos de usuario</p>
                        </div>
                    </div>
                </a>
                @endcan
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- No scripts needed for this dashboard -->
@endpush