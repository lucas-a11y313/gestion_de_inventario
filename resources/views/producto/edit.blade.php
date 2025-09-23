@extends('template')

@section('title', 'Editar Producto')

@push('css')
    <style>
        #descripcion {
            resize: none;
        }
    </style>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>{{-- Importar la libreria para utilizar el bootstrap select --}}
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Producto</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active">Editar producto</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('productos.update',['producto' => $producto]) }}" method="post" enctype="multipart/form-data">
                {{-- enctype="multipart/form-data": te permite enviar los archivos de tipo file a través del formulario --}}
                @method('PATCH')
                @csrf
                <div class="row g-3">

                    <!--Codigo-->
                    <div class="col-md-6 mb-2">
                        <label for="codigo" class="form-label">Código:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control"
                            value="{{ old('codigo',$producto->codigo) }}">
                        @error('codigo')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Nombre-->
                    <div class="col-md-6 mb-2">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre',$producto->nombre) }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Descripcion-->
                    <div class="col-md-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{ old('descripcion',$producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Fecha de vencimiento-->
                    <div class="col-md-6 mb-2">
                        <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento:</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"
                            value="{{ old('fecha_vencimiento',$producto->fecha_vencimiento) }}">
                        @error('fecha_vencimiento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Imagen-->
                    <div class="col-md-6 mb-2">
                        <label for="img_path" class="form-label">Imagen:</label>
                        <input type="file" name="img_path" id="img_path" class="form-control" accept="Image/*"
                            value="{{ old('img_path') }}">{{-- accept="Image/*" hace que el input (de tipo file) acepte todo tipo de imagenes --}}
                        @error('img_path')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Marcas-->
                    <div class="col-md-6 mb-2">
                        <label for="marca_id" class="form-label">Marca:</label>
                        <select data-size="4" title="Seleccione una marca" data-live-search="true" name="marca_id"
                            id="marca_id" class="form-control selectpicker show-tick">
                            @foreach ($marcas as $marca)
                                @if ($producto->marca_id == $marca->id)
                                    <option selected value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                                @else
                                    <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                                @endif
                            @endforeach
                        </select>{{-- Dentro del select vamos a poder poner un listado de todas nuestras marcas --}}
                        @error('marca_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Categorías-->
                    <div class="col-md-6 mb-2">
                        <label for="categorias" class="form-label">Categorías:</label>
                        <select data-size="4" title="Seleccione las categorías" data-live-search="true" name="categorias[]"
                            id="categorias" class="form-control selectpicker show-tick" multiple>
                            @foreach ($categorias as $categoria)
                                @if (in_array($categoria->id, $producto->categorias->pluck('id')->toArray())){{--$producto->categorias->pluck('id')->toArray() convierte la colección de IDs en un array estándar de PHP, lo cual es compatible con in_array().--}}
                                    <option selected value="{{ $categoria->id }}"
                                        {{ in_array($categoria->id, old('categorias', [])) ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @else
                                    <option value="{{ $categoria->id }}"
                                        {{ in_array($categoria->id, old('categorias', [])) ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endif
                            @endforeach
                        </select>{{-- Dentro del select vamos a poder poner un listado de todas nuestras marcas --}}
                        @error('categorias')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Modelos-->
                    <div class="col-md-6 mb-2">
                        <label for="modelos" class="form-label">Modelo:</label>
                        <select data-size="4" title="Seleccione los modelos" data-live-search="true" name="modelos[]"
                            id="modelos" class="form-control selectpicker show-tick" multiple>
                            
                        </select>{{-- Dentro del select vamos a poder poner un listado de todas nuestras marcas --}}
                        @error('modelos')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <!--Destino del bien-->
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label">Destino del bien:</label>
                        <select data-size="4" title="Seleccione del destino" data-live-search="true" name=""
                            id="" class="form-control selectpicker show-tick" multiple>
                        </select>{{-- Dentro del select vamos a poder poner un listado de todas nuestras marcas --}}
                        @error('')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
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
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
