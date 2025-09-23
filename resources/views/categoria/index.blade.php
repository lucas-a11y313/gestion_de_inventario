@extends('template')

@section('title', 'Categorías')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    {{-- hay problema con este estilo de css, por eso sale todo encimado en la vista la tabla --}}
@endpush

@section('content')
    @if (session('success'))
        {{-- si nuestra sesion tiene como clave 'success'(que viene del "return redirect()" de categorias.store o categorias.update) entonces debe de aparecer la alerta 
        Session: Una sesión en Laravel es una forma de almacenar información sobre el usuario a lo largo de múltiples solicitudes HTTP.--}}
        <script>

            let message = "{{ session('success') }}";//asigna a la variable message el valor de la clave 'success' almacenada en la sesión de Laravel, que se pasa al frontend a través de Blade. Basicamente se le asigna a message la cadena o el valor que está relacionada a la clave 'success', por ejemplo: 'Categoría eliminada'
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

        <h1 class="mt-4 text-center">Categorías</h1>
        <ol class="breadcrumb mb-4">
            {{-- breadcrumb-item: Estiliza cada elemento de la lista para que se vea como un paso en la navegación.
            mb-4: Añade un margen inferior (margin-bottom) de 4 unidades, probablemente en un sistema de diseño como Bootstrap. --}}
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Categorías</li>{{-- active: Señala el elemento actual en la navegación (el que representa la página en la que estás). --}}
        </ol>

        @can('crear-categoria')
            <div class="mb-4">
                <a href="{{ route('categorias.create') }}">
                    <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                </a>
            </div>
        @endcan

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla categorías
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">{{-- class="table table-striped" hace que tu tabla tenga un estilo zebra(blanco y negro) una fila es gris y la otra blanca, de forma sucesiva van cambiando el color de las filas --}}
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr>
                                <td>
                                    {{ $categoria->caracteristica->nombre }}{{--en categoria quiero que me busques la relación que tenga con caracteristica y que através de eso me traiga el atributo nombre--}}
                                </td>
                                <td>
                                    {{ $categoria->caracteristica->descripcion }}
                                </td>
                                <td>
                                    @if ($categoria->caracteristica->estado == 1)
                                        <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>
                                    @else
                                        <span class="fw-bolder p-1 rounded bg-danger text-white">Eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        @can('editar-categoria')
                                            <form action="{{ route('categorias.edit', ['categoria' => $categoria]) }}"method="get">
                                                <button type="submit" class="btn btn-warning">Editar</button>
                                            </form>
                                        @endcan

                                        @can('eliminar-categoria')
                                            @if ($categoria->caracteristica->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $categoria->id }}">
                                                    Eliminar
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal-{{ $categoria->id }}">
                                                    Restaurar
                                                </button>
                                            @endif
                                        @endcan

                                    </div>
                                </td>
                            </tr>

                            <!--Un modal en Bootstrap es una ventana emergente que se utiliza para mostrar contenido adicional sin salir de la página actual. Los modales son muy útiles para mostrar información importante, formularios, imágenes o cualquier otro contenido que necesite la atención del usuario.-->
                            <div class="modal fade" id="confirmModal-{{ $categoria->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                {{-- #confirmModal-{{$categoria->id}}: acá estamos nombrando al modal que vamos a utilizar y le estamos enviando una variable que contiene el id que se utilizara para localizar el registro a eliminar --}}
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{$categoria->caracteristica->estado == 1 ? '¿Seguro que quieres eliminar la categoría?' : '¿Seguro que quieres restaurar la categoría?'}}
                                            {{-- Acá estamos aplicando un operador ternario que es lo mismo que el if-else, si id == 1 entonces que muestre lo de eliminar sino(sino se representa con ':'), entonces que muestre lo de restaurar--}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>

                                            <form
                                                action="{{ route('categorias.destroy', ['categoria' => $categoria->id]) }}"
                                                method="post">
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
