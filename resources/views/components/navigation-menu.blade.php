<div id="layoutSidenav_nav" class="sb-sidenav bg-gray-800 w-60 min-h-screen fixed left-0 top-16 z-40">
    <nav class="h-full flex flex-col">
        <div class="flex-1 pt-4">
            <div class="space-y-1">
                <!-- Inicio Section -->
                <div class="px-4 py-2 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                    Inicio
                </div>
                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('panel') }}">
                    <i class="fas fa-tachometer-alt mr-3 w-4"></i>
                    Panel
                </a>

                <!---div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Layouts
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="layout-static.html">Static Navigation</a>
                        <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Pages
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Authentication
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="login.html">Login</a>
                                <a class="nav-link" href="register.html">Register</a>
                                <a class="nav-link" href="password.html">Forgot Password</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                            Error
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="401.html">401 Page</a>
                                <a class="nav-link" href="404.html">404 Page</a>
                                <a class="nav-link" href="500.html">500 Page</a>
                            </nav>
                        </div>
                    </nav>
                </div--->
                <!-- Módulos Section -->
                <div class="px-4 py-2 mt-6 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                    Módulos
                </div>

                @can('ver-cliente')
                    <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                       href="{{ route('clientes.index') }}">
                        <i class="fa-solid fa-users mr-3 w-4"></i>
                        Funcionarios
                    </a>
                @endcan

                @can('ver-producto')
                    <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                       href="{{ route('productos.index') }}">
                        <i class="fa-solid fa-cart-plus mr-3 w-4"></i>
                        Productos
                    </a>
                @endcan

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

                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="#">
                    <i class="fa-solid fa-clipboard-list mr-3 w-4"></i>
                    Modelos
                </a>

                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('inventarioBP') }}">
                    <i class="fa-solid fa-clipboard-list mr-3 w-4"></i>
                    Inventario de BP
                </a>

                <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                   href="{{ route('inventarioIN') }}">
                    <i class="fa-solid fa-clipboard-list mr-3 w-4"></i>
                    Inventario de Insumos
                </a>
                
                @can('ver-proveedor')
                    <!--
                    <a class="nav-link" href="{{ route('proveedores.index') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-truck-field"></i></div>
                        Proveedores
                    </a>
                    -->
                @endcan
                
                <!-- COMPRAS -->
                @can('ver-compra'){{--Solo los usuarios con el permiso de ver-compra van a poder ver en la vista la opción Compras--}}
                <!--
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCompras" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-store"></i></div>
                        Compras
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseCompras" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('compras.index') }}">Ver</a>
                            <a class="nav-link" href="{{ route('compras.create') }}">Crear</a>
                        </nav>
                    </div>
                -->
                @endcan

                <!-- VENTAS -->
                @can('ver-venta')
                <!--
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVentas" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        Ventas
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseVentas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('ventas.index') }}">Ver</a>
                            <a class="nav-link" href="{{ route('ventas.create') }}">Crear</a>
                        </nav>
                    </div>
                -->
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
                    <a class="nav-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors"
                       href="{{ route('roles.index') }}">
                        <i class="fa-solid fa-person-circle-plus mr-3 w-4"></i>
                        Roles
                    </a>
                @endcan
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-700 p-4">
            <div class="text-xs text-gray-400 mb-1">Bienvenido:</div>
            <div class="text-sm text-white font-medium">{{ auth()->user()->name }}</div>
        </div>
    </nav>
</div>