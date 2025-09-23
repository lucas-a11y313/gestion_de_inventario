<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Sistema de ventas" />
        <meta name="author" content="Lucas" />
        <title>Sistema ventas - @yield('title')</title>
        @vite(['resources/css/app.css'])
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @stack('css')
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    </head>

    @auth<!--Esta directiva hace que muestre el body solo a los usuarios autenticados-->
        <body class="sb-nav-fixed pt-16">

            <x-navigation-header></x-navigation-header>

            <div id="layoutSidenav">

                <x-navigation-menu></x-navigation-menu>

                <div id="layoutSidenav_content">
                    
                    <main>
                        @yield('content')<!--- content o contenido--->
                    </main>
                    
                    <x-footer></x-footer>

                </div>
            </div>
            @vite(['resources/js/app.js'])
            <script src="{{ asset('js/scripts.js') }}"></script>
            @stack('js')
            
        </body>
    @endauth

    @guest<!--Esta directiva hace que se muestre la vista 401 para los usuarios no autenticados-->
        @include('errors.401')<!--Esta directiva te permite mostrar una vista-->
    @endguest

</html>