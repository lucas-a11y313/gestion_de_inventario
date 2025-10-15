@extends('template')

@section('title', 'Editar Inventario Insumos')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Insumo</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventarioinsumos.index') }}">Inventario de Insumos</a></li>
            <li class="breadcrumb-item active">Editar Insumo</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <div class="card-body">
                <form>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código del producto:</label>
                            <input type="text" class="form-control" id="codigo" value="INS-001">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre del producto:</label>
                            <input type="text" class="form-control" id="nombre" value="Alcohol en gel">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" value="25">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="retirado" class="form-label">Retirado por:</label>
                            <input type="text" class="form-control" id="retirado" value="Juan Pérez">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="razon" class="form-label">Razón del retiro:</label>
                            <textarea class="form-control" id="razon" rows="2">Uso de oficina</textarea>
                        </div>

                        <!--Botones-->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <button type="reset" class="btn btn-secondary">Reiniciar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
