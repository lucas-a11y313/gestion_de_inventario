@extends('template')

@section('title', 'Editar proveedores')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select --}}
@endpush

@section('content')
    <div class="container-fluid px-4">

        <h1 class="mt-4 text-center">Editar Proveedor</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item active">Editar proveedor</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('proveedores.update',['proveedore' => $proveedore]) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="row g-3">

                    <!--Tipo de persona-->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_persona" class="form-label">Tipo de proveedor: <span class="fw-bold">{{strtoupper($proveedore->persona->tipo_persona)}}</span></label>
                        @error('tipo_persona')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
    
                    <!--Razón social-->
                    <div class="col-md-12 mb-2" id="box-razon-social">
                        @if ($proveedore->persona->tipo_persona == 'natural')
                            <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos:</label>
                        @else
                            <label id="label-juridico" for="razon_social" class="form-label">Nombre de la empresa:</label>
                        @endif
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social',$proveedore->persona->razon_social)}}">
                        @error('razon_social')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--Dirección-->
                    <div class="col-md-8 mb-2">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion',$proveedore->persona->direccion) }}">
                        @error('direccion')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--Tipo de documento-->
                    <div class="col-md-6 mb-2">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select title="Seleccione una opción" name="documento_id" id="documento_id" class="form-control selectpicker show-tick" data-live-search="true">
                           @foreach ($documentos as $documento)
                                @if ($proveedore->persona->documento->tipo_documento == $documento->tipo_documento)
                                    <option value="{{$documento->id}}" selected>{{$documento->tipo_documento}}</option>
                                @else
                                    <option value="{{$documento->id}}">{{$documento->tipo_documento}}</option>
                                @endif
                           @endforeach 
                        </select>
                        @error('documento_id')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--Número de documento-->
                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Número de documento:</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento',$proveedore->persona->numero_documento) }}">
                        @error('numero_documento')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <button type="reset" class="btn btn-secondary">Reiniciar</button>
                    </div>

                </div>
            </form>

        </div>

    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush