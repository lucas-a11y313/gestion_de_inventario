@extends('template')

@section('title', 'Clientes')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    {{-- hay problema con este estilo de css, por eso sale todo encimado en la vista la tabla --}}
@endpush

@section('content')
    @if (session('success'))
        {{-- si nuestra sesion tiene como clave 'success'(que viene del "return redirect()" de categorias.store o categorias.update) entonces debe de aparecer la alerta 
        Session: Una sesión en Laravel es una forma de almacenar información sobre el usuario a lo largo de múltiples solicitudes HTTP. --}}
        <script>
            let message = "{{ session('success') }}"; //asigna a la variable message el valor de la clave 'success' almacenada en la sesión de Laravel, que se pasa al frontend a través de Blade. Basicamente se le asigna a message la cadena o el valor que está relacionada a la clave 'success', por ejemplo: 'Categoría eliminada'
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

        <h1 class="mt-4 text-center">Funcionarios</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Funcionarios</li>
        </ol>

        @can('crear-cliente')
            <div class="mb-4">
                <a href="{{ route('clientes.create') }}">
                    <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                </a>
            </div>
        @endcan

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla clientes
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">{{-- class="table table-striped" hace que tu tabla tenga un estilo zebra(blanco y negro) una fila es gris y la otra blanca, de forma sucesiva van cambiando el color de las filas --}}
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Documento</th>
                            <th>Tipo de persona</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td>
                                    {{ $cliente->persona->razon_social }}
                                </td>
                                <td>
                                    {{ $cliente->persona->direccion }}
                                </td>
                                <td>
                                    <p class="fw-semibold mb-1">{{ $cliente->persona->documento->tipo_documento }}:</p>
                                    <p class="text-muted m-0">{{ $cliente->persona->numero_documento }}</p>
                                </td>
                                <td>
                                    {{ $cliente->persona->tipo_persona }}
                                </td>
                                <td>
                                    @if ($cliente->persona->estado == 1)
                                        <div class="container">
                                            <div class="row">
                                                <span class="fw-bolder p-1 bg-success text-center text-white rounded-pill">Activo</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="container">
                                            <div class="row">
                                                <span class="fw-bolder p-1 bg-danger text-center text-white rounded-pill">Eliminado</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        
                                        @can('editar-cliente')
                                            <form action="{{ route('clientes.edit', ['cliente' => $cliente]) }}" method="get">
                                                <button type="submit" class="btn btn-warning">Editar</button>
                                            </form>
                                        @endcan
                                        
                                        @can('eliminar-cliente')
                                            @if ($cliente->persona->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $cliente->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $cliente->id }}">Restaurar</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="confirmModal-{{ $cliente->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación:</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $cliente->persona->estado == 1 ? '¿Estás seguro de que quieres eliminar al cliente?' : '¿Estás seguro de que quieres restaurar al cliente?' }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                            <form action="{{ route('clientes.destroy',['cliente' => $cliente->id]) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Confirmar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
