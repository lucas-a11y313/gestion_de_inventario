@extends('template')

@section('title', 'Inventario de BP')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Inventario de BP</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item active">Inventario de BP</li>
        </ol>

        <div class="row">
            <div class="col-md-9">
                <div class="mb-4">
                    <a href="#">
                        <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                    </a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="mb-4">
                    <a href="{{ route('productos.inventario.pdf') }}" target="_blank">
                        <button type="button" class="btn btn-primary">Generar informe del inventario</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-warehouse me-1"></i>
                Tabla Inventario
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>BP</th>
                            <th>Código del producto</th>
                            <th>Nombre del producto</th>
                            <th>Propietario</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ejemplo de filas estáticas solo para mockup --}}
                        <tr>
                            <td>BP-001</td>
                            <td>PRD-123</td>
                            <td>Notebook Dell</td>
                            <td>Raj Kumar</td>
                            <td>1</td>
                            <td><span class="fw-bolder p-1 rounded bg-success text-white">Activo</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm">Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>BP-002</td>
                            <td>PRD-456</td>
                            <td>Proyector Epson</td>
                            <td>Sergio Morel</td>
                            <td>1</td>
                            <td><span class="fw-bolder p-1 rounded bg-success text-white">Activo</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm">Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>BP-003</td>
                            <td>PRD-789</td>
                            <td>Monitor LG 24"</td>
                            <td>Jorge Zarza</td>
                            <td>1</td>
                            <td><span class="fw-bolder p-1 rounded bg-success text-white">Activo</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-sm">Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm">Eliminar</button>
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
