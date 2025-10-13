@extends('template')

@section('title', 'Editar Categoría')

@push('css')
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Editar Categoría</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('categorias.index') }}">Categorías</a></div>
                <div class="breadcrumb-item active">Editar categoría</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Categoría
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.update', ['categoria' => $categoria]) }}" method="post">
                        @method('PATCH')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Nombre -->
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" name="nombre" id="nombre" class="form-input"
                                    value="{{ old('nombre', $categoria->caracteristica->nombre) }}" placeholder="Ej: Herramientas">
                                <p class="text-sm text-gray-500 mt-1">Nombre actual: <strong>{{ $categoria->caracteristica->nombre }}</strong></p>
                                @error('nombre')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="form-group md:col-span-2">
                                <label for="descripcion" class="form-label">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" rows="3" class="form-input resize-none"
                                    placeholder="Ingrese una descripción para la categoría">{{ old('descripcion', $categoria->caracteristica->descripcion) }}</textarea>
                                <p class="text-sm text-gray-500 mt-1">Descripción actual: <strong>{{ $categoria->caracteristica->descripcion ?? 'Sin descripción' }}</strong></p>
                                @error('descripcion')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-center mt-6 space-x-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar
                            </button>
                            <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
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
@endpush
