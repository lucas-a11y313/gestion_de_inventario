@extends('template')

@section('title', 'Ventas')

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
                timer: 3000,
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
        <h1 class="mt-4 text-center">Ventas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Ventas</li>
        </ol>

        @can('crear-venta')
            <div class="mb-4">
                <a href="{{ route('ventas.create') }}">
                    <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                </a>
            </div>
        @endcan

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla ventas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">{{-- class="table table-striped" hace que tu tabla tenga un estilo zebra(blanco y negro) una fila es gris y la otra blanca, de forma sucesiva van cambiando el color de las filas --}}
                    <thead>
                        <tr>
                            <th>Comprobante</th>
                            <th>Cliente</th>
                            <th>Fecha y hora</th>
                            <th>Usuario</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                                <td>
                                    <p class="fw-semibold mb-1">{{ $venta->comprobante->tipo_comprobante }}:</p>
                                    <p class="text-muted m-0">{{ $venta->numero_comprobante }}</p>
                                </td>
                                <td>
                                    <p class="fw-semibold mb-1">{{ ucfirst($venta->cliente->persona->tipo_persona) }}:</p>{{-- Ucfirst convierte la primera letra en mayúscula --}}
                                    <p class="text-muted m-0">{{ $venta->cliente->persona->razon_social }}</p>
                                </td>
                                <td>
                                    {{-- Cambiamos el formato de fecha a dia-mes-año y hora al fomrato horas-minutos --}}
                                    {{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d-m-Y') . ' ' . \Carbon\Carbon::parse($venta->fecha_hora)->format('H:i') }}
                                </td>
                                <td>{{ $venta->user->name }}</td>
                                <td>{{ $venta->total }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        @can('mostrar-venta')
                                            <form action="{{ route('ventas.show', ['venta' => $venta]) }}" method="get">
                                                <button type="submit" class="btn btn-success">Ver</button>
                                            </form>
                                        @endcan

                                        @can('eliminar-venta')
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $venta->id }}">Eliminar</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="confirmModal-{{ $venta->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación:</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que quieres eliminar la compra?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('ventas.destroy', ['venta' => $venta->id]) }}" method="post">
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
