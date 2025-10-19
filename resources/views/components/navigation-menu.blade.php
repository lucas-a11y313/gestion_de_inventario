<div id="layoutSidenav_nav"
     class="bg-gray-800 w-60 transition-transform duration-300"
     style="position: fixed !important; left: 0 !important; top: 4rem !important; height: calc(100vh - 4rem) !important; z-index: 40 !important;"
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Menu scrolleable with padding for bottom section -->
    <div class="overflow-y-auto" style="height: calc(100% - 70px); padding-bottom: 1rem;">
        <div class="space-y-1 pt-4">
            <!-- Inicio Section -->
            <div class="px-4 py-2 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                Inicio
            </div>
            <a class="nav-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
               href="{{ route('panel') }}">
                <i class="fas fa-tachometer-alt mr-3 w-4"></i>
                Panel
            </a>

            <!-- Módulos Section -->
            <div class="px-4 py-2 mt-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                Módulos
            </div>

            @can('ver-adquisicion')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('adquisiciones.index') }}">
                    <i class="fa-solid fa-cart-shopping mr-3 w-4"></i>
                    Adquisiciones
                </a>
            @endcan

            @can('ver-solicitud')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('solicitudes.index') }}">
                    <i class="fa-solid fa-file-invoice mr-3 w-4"></i>
                    Solicitudes
                </a>
            @endcan

            @can('ver-proyecto')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('proyectos.index') }}">
                    <i class="fa-solid fa-project-diagram mr-3 w-4"></i>
                    Proyectos
                </a>
            @endcan

            @can('ver-producto')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('productos.index') }}">
                    <i class="fa-solid fa-cart-plus mr-3 w-4"></i>
                    Productos
                </a>
            @endcan

            @can('ver-inventarioBP')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('inventariobp.index') }}">
                    <i class="fa-solid fa-clipboard-list mr-3 w-4"></i>
                    Inventario de BP
                </a>
            @endcan

            <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
               href="{{ route('inventarioinsumos.index') }}">
                <i class="fa-solid fa-clipboard-list mr-3 w-4"></i>
                Inventario de Insumos
            </a>

            @can('ver-categoria')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('categorias.index') }}">
                    <i class="fa-solid fa-tag mr-3 w-4"></i>
                    Categorías
                </a>
            @endcan

            @can('ver-marca')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('marcas.index') }}">
                    <i class="fa-solid fa-bullhorn mr-3 w-4"></i>
                    Marcas
                </a>
            @endcan



            @can('ver-proveedor')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('proveedores.index') }}">
                    <i class="fa-solid fa-truck-field mr-3 w-4"></i>
                    Proveedores
                </a>
            @endcan

            @canany(['ver-user','ver-role'])
                <!-- Otros Section -->
                <div class="px-4 py-2 mt-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                    Otros
                </div>
            @endcanany

            @can('ver-user')
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('users.index') }}">
                    <i class="fa-solid fa-user mr-3 w-4"></i>
                    Usuarios
                </a>
            @endcan

            @can('ver-role')
            @endcan
            <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
               href="{{ route('roles.index') }}">
                <i class="fa-solid fa-person-circle-plus mr-3 w-4"></i>
                Roles
            </a>
        </div>
    </div>

    <!-- Usuario Section - Fixed at absolute bottom -->
    <div class="absolute bottom-0 left-0 right-0 border-t border-gray-700 bg-gray-900 px-4 py-3"
         style="height: 70px; z-index: 100;">
        <div class="text-xs text-gray-400 mb-1">Bienvenido:</div>
        <div class="text-sm text-white font-medium">{{ auth()->user()->name }}</div>
    </div>

</div>
