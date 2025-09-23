@extends('template')

@section('title', 'Crear proveedores')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select --}}
    <style>
        #box-razon-social{
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        <h1 class="mt-4 text-center">Crear Proveedor</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
            <li class="breadcrumb-item active">Crear proveedor</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('proveedores.store') }}" method="post">
                @csrf

                <div class="row g-3">

                    <!--Tipo de persona-->
                    <div class="col-md-6 mb-2">
                        <label for="tipo_persona" class="form-label">Tipo de proveedor:</label>
                        <select title="Seleccione una opción" name="tipo_persona" id="tipo_persona" class="form-control selectpicker show-tick" data-live-search="true">
                            <option value="natural" {{old('tipo_persona') == 'natural' ? 'selected' : ''}}>Persona natural</option>
                            <option value="juridica" {{old('tipo_persona') == 'juridica' ? 'selected' : ''}}>Persona juridica</option>
                        </select>
                        @error('tipo_persona')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
    
                    <!--Razón social-->
                    <div class="col-md-12 mb-2" id="box-razon-social">
                        <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos:</label>
                        <label id="label-juridico" for="razon_social" class="form-label">Nombre de la empresa:</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social')}}">
                        @error('razon_social')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--Dirección-->
                    <div class="col-md-8 mb-2">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}">
                        @error('direccion')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--Tipo de documento-->
                    <div class="col-md-6 mb-2">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select title="Seleccione una opción" name="documento_id" id="documento_id" class="form-control selectpicker show-tick" data-live-search="true">
                           @foreach ($documentos as $documento)
                               <option value="{{$documento->id}}" {{old('documento_id') == $documento->id ? 'selected' : ''}}>{{$documento->tipo_documento}}</option>
                           @endforeach 
                        </select>
                        @error('documento_id')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--Número de documento-->
                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Número de documento:</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{ old('numero_documento') }}">
                        @error('numero_documento')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </div>
            </form>

        </div>

    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function(){

            //cuando mi select "tipo_persona" cambie(haga un change) se ejecutará una función
            $('#tipo_persona').on('change',function(){

                //"$(this)" hace referencia al select '#tipo_persona', por lo cual el ".val()" trae el valor que está en el select y se asigna a la variable selectValue
                let selectValue = $(this).val();

                //"selectValue" puede ser natural o juridica
                if (selectValue == 'natural') {
                    $('#label-juridico').hide();//se oculta el label con id ="label-juridica" con hide()
                    $('#label-natural').show();//se muestra el label con id ="label-natural" con show()
                } else {
                    $('#label-natural').hide();
                    $('#label-juridico').show();
                }

                $('#box-razon-social').show();
            });
        });
    </script>
@endpush