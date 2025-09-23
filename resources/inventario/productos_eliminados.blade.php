@extends('template')

@section('title', 'Productos')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    {{-- hay problema con este estilo de css, por eso sale todo encimado en la vista la tabla --}}
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}"
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        </script>
    @endif
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Productos eliminados</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">Productos eliminados</li>
        </ol>

        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla productos
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">{{-- class="table table-striped" hace que tu tabla tenga un estilo zebra(blanco y negro) una fila es gris y la otra blanca, de forma sucesiva van cambiando el color de las filas --}}
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Categorías</th>
                            <th>Sugerencia de destino del bien</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                            
                            </td>
                            <td></td>
                            <td>
                                
                                <div class="container">
                                    <div class="row">
                                        <span class="fw-bolder p-1 bg-danger rounded-pill text-white text-center">Eliminado</span>
                                    </div>
                                </div>
                                
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">


                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="">Ver</button>

                                    @can('eliminar-producto')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="">Restaurar</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
