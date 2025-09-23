@extends('template')

@section('title', 'Proveedores')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    {{-- hay problema con este estilo de css, por eso sale todo encimado en la vista la tabla --}}
@endpush

@section('content')

    @if (session('success'))
        <script>
            let message = "{{ session('success') }}";
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

        <h1 class="mt-4 text-center">Proveedores</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Proveedores</li>
        </ol>

        @can('crear-proveedor')
            <div class="mb-4">
                <a href="{{ route('proveedores.create') }}">
                    <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                </a>
            </div>
        @endcan

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla proveedores
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
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
                        @foreach ($proveedores as $proveedore)
                            <tr>
                                <td>
                                    {{ $proveedore->persona->razon_social }}
                                </td>
                                <td>
                                    {{ $proveedore->persona->direccion }}
                                </td>
                                <td>
                                    <p class="fw-normal mb-1">{{ $proveedore->persona->documento->tipo_documento }}:</p>
                                    <p class="text-muted m-0">{{ $proveedore->persona->numero_documento }}</p>
                                </td>
                                <td>
                                    {{ $proveedore->persona->tipo_persona }}
                                </td>
                                <td>
                                    @if ($proveedore->persona->estado == 1)
                                        <div class="container">
                                            <div class="row">
                                                <span
                                                    class="fw-bolder p-1 bg-success text-white text-center border rounded-pill ">Activo</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="container">
                                            <div class="row">
                                                <span
                                                    class="fw-bolder p-1 bg-danger text-white text-center border rounded-pill ">Eliminado</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        @can('editar-proveedor')
                                            <form action="{{ route('proveedores.edit', ['proveedore' => $proveedore]) }}"
                                                method="get">
                                                <button type="submit" class="btn btn-warning">Editar</button>
                                            </form>
                                        @endcan

                                        @can('eliminar-proveedor')
                                            @if ($proveedore->persona->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $proveedore->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $proveedore->id }}">Restaurar</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="confirmModal-{{ $proveedore->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación:</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{$proveedore->persona->estado == 1 ? '¿Estás seguro de que quieres eliminar al proveedor?' : '¿Estás seguro de que quieres restaurar al proveedor?'}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                            <form action="{{route( 'proveedores.destroy',['proveedore' => $proveedore->id])}}" method="post">
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
