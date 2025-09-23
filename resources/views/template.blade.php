<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Sistema de ventas" />
        <meta name="author" content="Lucas" />
        <title>Sistema ventas - @yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="{{ asset('css/template.css') }}" rel="stylesheet" />{{-- La función "asset()" es propia de Laravel y se utiliza para generar una URL absoluta al archivo especificado dentro de la carpeta public. Basicamente asset() crea una URL que te lleva a la carpeta public y desde ahí empieza a buscar otras carpetas o archivos.
        "css/template.css" se refiere al archivo CSS que debería estar ubicado en public/css/template.css. --}}
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        @stack('css')
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    </head>

    @auth<!--Esta directiva hace que muestre el body solo a los usuarios autenticados-->
        <body class="sb-nav-fixed">

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
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <script src="{{ asset('js/scripts.js') }}"></script>
            @stack('js')
            
        </body>
    @endauth

    @guest<!--Esta directiva hace que se muestre la vista 401 para los usuarios no autenticados-->
        @include('errors.401')<!--Esta directiva te permite mostrar una vista-->
    @endguest

</html>