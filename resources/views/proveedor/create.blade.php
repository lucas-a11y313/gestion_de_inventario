@extends('template')

@section('title', 'Crear proveedores')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <style>
        #box-razon-social{
            display: none;
        }

        /* Fix bootstrap-select styling to work with Tailwind */
        .bootstrap-select .dropdown-toggle {
            background-color: white !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
        }

        .bootstrap-select .dropdown-menu {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
        }

        /* Fix navigation styles - Override Bootstrap */
        .nav-link {
            padding: 0.5rem 1rem !important;
            display: flex !important;
            align-items: center !important;
            color: #d1d5db !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
        }

        .nav-link:hover {
            background-color: #374151 !important;
            color: white !important;
        }

        .nav-link i {
            margin-right: 0.75rem !important;
            width: 1rem !important;
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
                                <select title="Seleccione una opción" name="tipo_persona" id="tipo_persona" class="form-control selectpicker show-tick" data-live-search="true">
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
                                <select title="Seleccione una opción" name="documento_id" id="documento_id" class="form-control selectpicker show-tick" data-live-search="true">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        // Esperar a que todo esté completamente cargado
        window.addEventListener('load', function() {
            console.log('Initializing selectpicker...');

            // Inicializar selectpicker
            $('.selectpicker').selectpicker({
                style: '',
                styleBase: 'form-control'
            });

            console.log('Selectpicker initialized');

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