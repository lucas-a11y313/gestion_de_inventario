@extends('template')

@section('title', 'Crear Inventario Insumos')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Insumo</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item"><a href="#">Inventario de Insumos</a></li>
            <li class="breadcrumb-item active">Crear Insumo</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código del producto:</label>
                            <input type="text" class="form-control" id="codigo" placeholder="Ej: INS-001">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre del producto:</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Ej: Alcohol en gel">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" placeholder="Ej: 25">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="retirado" class="form-label">Retirado por:</label>
                            <input type="text" class="form-control" id="retirado" placeholder="Ej: Juan Pérez">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="razon" class="form-label">Razón del retiro:</label>
                            <textarea class="form-control" id="razon" rows="2" placeholder="Ej: Uso de oficina"></textarea>
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
