<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Sistema de ventas" />
        <meta name="author" content="Lucas" />
        <title>SGI PTI - @yield('title')</title>
        @vite(['resources/css/app.css'])
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @stack('css')
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    </head>

    @auth<!--Esta directiva hace que muestre el body solo a los usuarios autenticados-->
        <body class="sb-nav-fixed bg-gray-50 min-h-screen flex flex-col" x-data="{ sidebarOpen: true }">

            <x-navigation-header></x-navigation-header>

            <!-- Mobile sidebar overlay -->
            <div x-show="sidebarOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden" style="display: none;"></div>

            <div class="flex flex-1 pt-16">
                <x-navigation-menu></x-navigation-menu>

                <div class="flex-1 flex flex-col transition-all duration-300 md:ml-0"
                     :class="sidebarOpen ? 'md:ml-60' : 'md:ml-0'">

                    <main class="flex-1 p-0">
                        @yield('content')<!--- content o contenido--->
                    </main>

                    <x-footer></x-footer>

                </div>
            </div>
            @vite(['resources/js/app.js'])
            @stack('js')

        </body>
    @endauth

    @guest<!--Esta directiva hace que se muestre la vista 401 para los usuarios no autenticados-->
        @include('errors.401')<!--Esta directiva te permite mostrar una vista-->
    @endguest

</html>