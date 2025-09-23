@extends('template')

@section('title', 'Editar marcas')

@push('css')
    <style>
        #descripcion{
            resize: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Marcas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('marcas.index') }}">Marcas</a></li>
            <li class="breadcrumb-item active">Editar marca</li>
        </ol>
    </div>

    <div class="container w-100 border border-3 border-primary rounded p-4 smt-3">
        <form action=" {{ route('marcas.update', ['marca' => $marca]) }}" method="post">
            @method('PATCH')
            @csrf
            <div class="row g-3">
                <div class="col">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $marca->caracteristica->nombre) }}">
                    @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripcion:</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion',$marca->caracteristica)}}</textarea>
                    @error('descripcion')
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
@endsection

@push('js')
@endpush
