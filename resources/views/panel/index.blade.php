@php
    use App\Models\Cliente;
    use App\Models\Categoria;
    use App\Models\Compra;
    use App\Models\Marca;
    use App\Models\Producto;
    use App\Models\Proveedore;
    use App\Models\User;
    use App\Models\Venta;
@endphp

@extends('template')<!---index es una vista que va a heredar de template, Indica que una vista hija hereda de una plantilla base(en este caso, template). Permite reutilizar el diseño definido en la plantilla base.--->

@section('title','Panel')<!--- Define el contenido de una sección que será mostrada en un yield o stack en la plantilla base, en este caso es de un yield llamado title. --->

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush<!---Añade más contenido a una sección o pila. Añade contenido a una "pila" definida con stack.Se usa en las vistas hijas o incluso en subcomponentes para insertar contenido acumulativamente. En este caso estaremos añadiendo contenido en la pila css que esta en stack--->

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


    <div class="container-fluid px-4">
        <h1 class="mt-4">Panel</h1>
        
        <ol class="breadcrumb mb-4">
            <!--<li class="breadcrumb-item active">Panel</li>-->
        </ol>
        <div class="row">
            <!--Clientes-->
            @can('ver-cliente')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-people-group"></i><span class="m-1">Funcionarios</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $cliente = count(Cliente::all());//Me cuenta cuantos clientes tengo
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$cliente}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('clientes.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Productos-->
            @can('ver-producto')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-cart-plus"></i><span class="m-1">Productos</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $producto = count(Producto::all()); 
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$producto}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Categorías-->
            @can('ver-categoria')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-tag"></i><span class="m-1">Categorías</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $categoria = count(Categoria::all());//Me cuenta cuantas categorías tengo
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$categoria}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('categorias.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Marcas-->
            @can('ver-marca')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-bullhorn"></i><span class="m-1">Marcas</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $marca = count(Marca::all());//Me cuenta cuantas marcas tengo
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$marca}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('marcas.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Compras-->
            @can('ver-compra')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-store"></i><span class="m-1">Compras</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $compra = count(Compra::all());//Me cuenta cuantas compras tengo
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$compra}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('compras.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Proveedores-->
            @can('ver-proveedor')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-truck-field"></i><span class="m-1">Proveedores</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $proveedore = count(Proveedore::all());
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$proveedore}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('proveedores.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Users-->
            @can('ver-user')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-user"></i><span class="m-1">Usuarios</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $user = count(User::all());
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$user}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('users.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            <!--Ventas-->
            @can('ver-venta')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                <i class="fa-solid fa-user"></i><span class="m-1">Ventas</span>
                                </div>
                                <div class="col-4">
                                    @php
                                        $venta = count(Venta::all());
                                    @endphp
                                    <p class="text-center fw-bold fs-4">{{$venta}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('ventas.index') }}">Ver más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush