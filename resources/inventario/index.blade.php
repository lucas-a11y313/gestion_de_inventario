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
        <h1 class="mt-4 text-center">Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>

        @can('crear-producto')
            <div class="row">
                <div class="col-md-9">
                    <div class="mb-4">
                        <a href="{{ route('productos.create') }}">
                            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-4">
                        <a href="{{ route('productos.inventario.pdf') }}" target="_blank">
                            <button type="button" class="btn btn-primary">Insumos eliminados</button>
                        </a>
                    </div>
                </div>
                <!--
                <div class="col-md-3">
                    <div class="mb-4">
                        <a href="{{ route('productos.inventario.pdf') }}" target="_blank">
                            <button type="button" class="btn btn-primary">Generar informe del inventario</button>
                        </a>
                    </div>
                </div>
                -->
            </div>
        @endcan
        
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
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->codigo }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->marca->caracteristica->nombre }}</td>
                                <td>
                                    @foreach ($producto->categorias as $categoria)
                                        <div class="container">
                                            <div class="row">
                                                <span class="m-1 p-1 bg-secondary text-white text-center rounded-pill">
                                                    {{ $categoria->caracteristica->nombre }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td></td>
                                <td>
                                    @if ($producto->estado == 1)
                                        <div class="container">
                                            <div class="row">
                                                <span class="fw-bolder p-1 bg-success rounded-pill text-white text-center">Activo</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="container">
                                            <div class="row">
                                                <span class="fw-bolder p-1 bg-danger rounded-pill text-white text-center">Eliminado</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        @can('editar-producto')
                                            <form action="{{ route('productos.edit', ['producto' => $producto]) }}" method="get">
                                                <button type="submit" class="btn btn-warning">Editar</button>
                                            </form>
                                        @endcan

                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#verModal-{{ $producto->id }}">Ver
                                        </button>

                                        @can('eliminar-producto')
                                            @if ($producto->estado == 1)
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $producto->id }}">Eliminar</button>
                                            @else
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $producto->id }}">Restaurar</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            
                            <!--Un modal en Bootstrap es una ventana emergente que se utiliza para mostrar contenido adicional sin salir de la página actual. Los modales son muy útiles para mostrar información importante, formularios, imágenes o cualquier otro contenido que necesite la atención del usuario.-->
                            <div class="modal fade" id="verModal-{{ $producto->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                {{-- verModal-{{ $producto->id }}: acá estamos nombrando al modal que vamos a utilizar y le estamos enviando una variable que contiene el id que se utilizara para localizar el registro a mostrar --}}
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del producto:</h1>
                                            <button type="button" class="btn-close"
                                                data-bs-dismiss="modal"aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <label><span class="fw-bolder">Descripción:</span>  {{ $producto->descripcion }}</label>
                                            </div>
                                            <div class="row mb-3">
                                                <label><span class="fw-bolder">Fecha de vencimiento:</span>  {{ $producto->fecha_vencimiento == '' ? 'No tiene' : $producto->fecha_vencimiento }}</label>
                                            </div>
                                            <!--
                                            <div class="row mb-3">
                                                <label><span class="fw-bolder">Stock:</span>  {{ $producto->stock }}</label>
                                            </div>
                                            -->
                                            <div class="row mb-3">
                                                {{--HAY QUE SACAR ESTO DEBIDO A LA SOLICITUD DEL CLIENTE--}}
                                                <label><span class="fw-bolder">Imagen:</span></label>
                                                <div>
                                                    @if ($producto->img_path != null)
                                                        <img src="{{ Storage::url('productos/' . $producto->img_path) }}" alt="{{ $producto->nombre }}" class="img-fluid img-thumbnail border border-4 rounded">

                                                    @else
                                                        <img src="" alt="{{ $producto->nombre }}">{{-- esta parte me aparece raro cuando el producto no tiene imagen --}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="confirmModal-{{ $producto->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación:</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{$producto->estado == 1 ? '¿Seguro que quieres eliminar el producto?' : '¿Seguro que quieres restaurar el producto?'}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                                Cerrar
                                            </button>
                                            
                                            <form action="{{ route('productos.destroy',['producto' => $producto->id]) }}" method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Confirmar</button>
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
