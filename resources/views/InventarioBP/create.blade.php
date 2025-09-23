@extends('template')

@section('title', 'Crear Inventario BP')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear BP</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item"><a href="#">Inventario de BP</a></li>
            <li class="breadcrumb-item active">Crear BP</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="bp" class="form-label">BP:</label>
                            <input type="text" class="form-control" id="bp" placeholder="Ej: BP-001">
                        </div>
    
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código del producto:</label>
                            <input type="text" class="form-control" id="codigo" placeholder="Ej: PRD-123">
                        </div>
    
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre del producto:</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ej: Monitor LG 24">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Propietario:</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ej: Raj Kumar">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" placeholder="Ej: 1">
                        </div>
    
                        <!--Botones-->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush