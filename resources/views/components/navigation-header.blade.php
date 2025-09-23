<nav class="bg-gray-800 shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-full mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Navbar Brand-->
            <div class="flex items-center">
                <button class="text-gray-300 hover:text-white mr-4 lg:hidden" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="text-white font-bold text-lg" href="{{ route('panel') }}">Gestión de Inventario</a>
            </div>

            <!-- Search Bar (hidden on mobile) -->
            <form class="hidden md:flex items-center space-x-2">
                <div class="relative">
                    <input class="bg-gray-700 text-white placeholder-gray-400 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           type="text" placeholder="Buscar..." />
                    <button class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-gray-300 hover:text-white focus:outline-none">
                    <i class="fas fa-user fa-fw"></i>
                    <i class="fas fa-chevron-down ml-1 text-xs"></i>
                </button>

                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Configuraciones
                    </a>
                    <hr class="border-gray-200">
                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>