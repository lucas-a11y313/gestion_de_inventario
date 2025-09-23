@extends('template')

@section('title', 'Crear categorías')

@push('css')
    <style>
        #descripcion{
            resize: none;/*desactivar la propiedad que te permite cambiar el tamaño del textarea con el id:descripcion*/
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Categoría</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}">Categorías</a></li>
            <li class="breadcrumb-item active">Crear categoría</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            {{--  w-100: Aplica un ancho del 100% al contenedor o elemento al que se asigna.--}}
            <form action="{{ route('categorias.store') }}" method="post">
                @csrf{{-- la directiva @csrf sirve para poder enviar formularios --}}
                <div class="row g-3">
                    {{--g-3: Define un gap (espaciado) de 3 entre las columnas y filas dentro de la fila.--}}
                    <div class="col-md-6">
                        {{--col-md-6 define que el campo "Nombre" (o lo que pongas en esa columna) ocupará la mitad del espacio en pantallas medianas o mayores.--}}
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                        {{--La directiva @old sirve para mantener los campos que se envían cuando falla una validación--}}
                        @error('nombre'){{--si encuentra un error en el campo nombre, pues que me muestre el error--}}
                            <small class="text-danger">{{'*'.$message}}</small>{{--text-danger hace que este en rojo el texto--}}
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="descripcion" class="form-label">Descripcion:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion')}}</textarea>{{--el metodo old('descripcion') permite que lo que esta escrito en descripcion no se borre al seleccionar guardar--}}
                        @error('descripcion')
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
@endpush
