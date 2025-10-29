@extends('template')

@section('title', 'Crear proveedores')

@push('css')
    <style>
        #box-razon-social{
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Crear Proveedor</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></div>
                <div class="breadcrumb-item active">Crear proveedor</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Proveedor
                </div>
                <div class="card-body">
                    <form action="{{ route('proveedores.store') }}" method="post">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!--Tipo de persona-->
                            <div class="form-group">
                                <label for="tipo_persona" class="form-label">Tipo de proveedor:</label>
                                <select name="tipo_persona" id="tipo_persona" class="form-select">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="natural" {{old('tipo_persona') == 'natural' ? 'selected' : ''}}>Persona natural</option>
                                    <option value="juridica" {{old('tipo_persona') == 'juridica' ? 'selected' : ''}}>Persona juridica</option>
                                </select>
                                @error('tipo_persona')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Razón social-->
                            <div class="form-group md:col-span-2" id="box-razon-social">
                                <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos:</label>
                                <label id="label-juridico" for="razon_social" class="form-label">Nombre de la empresa:</label>
                                <input type="text" name="razon_social" id="razon_social" class="form-input" value="{{old('razon_social')}}">
                                @error('razon_social')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Dirección-->
                            <div class="form-group md:col-span-2">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" name="direccion" id="direccion" class="form-input" value="{{ old('direccion') }}">
                                @error('direccion')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Tipo de documento-->
                            <div class="form-group">
                                <label for="documento_id" class="form-label">Tipo de documento:</label>
                                <select name="documento_id" id="documento_id" class="form-select">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                   @foreach ($documentos as $documento)
                                       <option value="{{$documento->id}}" {{old('documento_id') == $documento->id ? 'selected' : ''}}>{{$documento->tipo_documento}}</option>
                                   @endforeach
                                </select>
                                @error('documento_id')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Número de documento-->
                            <div class="form-group">
                                <label for="numero_documento" class="form-label">Número de documento:</label>
                                <input type="text" name="numero_documento" id="numero_documento" class="form-input" value="{{ old('numero_documento') }}">
                                @error('numero_documento')
                                    <small class="form-error">{{'*'.$message}}</small>
                                @enderror
                            </div>

                        </div>

                        <!-- Botones -->
                        <div class="flex justify-center gap-3 mt-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Guardar
                            </button>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoPersonaSelect = document.getElementById('tipo_persona');
            const boxRazonSocial = document.getElementById('box-razon-social');
            const labelNatural = document.getElementById('label-natural');
            const labelJuridico = document.getElementById('label-juridico');

            // Cuando el select "tipo_persona" cambie, se ejecutará esta función
            tipoPersonaSelect.addEventListener('change', function() {
                const selectValue = this.value;

                // selectValue puede ser 'natural' o 'juridica'
                if (selectValue === 'natural') {
                    labelJuridico.style.display = 'none'; // Oculta el label jurídico
                    labelNatural.style.display = 'block'; // Muestra el label natural
                } else if (selectValue === 'juridica') {
                    labelNatural.style.display = 'none'; // Oculta el label natural
                    labelJuridico.style.display = 'block'; // Muestra el label jurídico
                }

                boxRazonSocial.style.display = 'block'; // Muestra el campo de razón social
            });
        });
    </script>
@endpush