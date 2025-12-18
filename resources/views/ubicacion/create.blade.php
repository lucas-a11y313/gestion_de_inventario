@extends('template')

@section('title', 'Crear Ubicación')

@push('css')
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Crear Ubicación</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('ubicaciones.index') }}">Ubicaciones</a></div>
                <div class="breadcrumb-item active">Crear ubicación</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Ubicación
                </div>
                <div class="card-body">
                    <form action="{{ route('ubicaciones.store') }}" method="post">
                        @csrf
                        <div class="grid grid-cols-1 gap-6"> {{-- Removed md:grid-cols-2 as there's only one field --}}

                            <!-- Nombre -->
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" name="nombre" id="nombre" class="form-input"
                                    value="{{ old('nombre') }}" placeholder="Ej: Almacén Principal">
                                <p class="text-sm text-gray-500 mt-1">Ingrese el nombre de la ubicación</p>
                                @error('nombre')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            {{-- Removed description field --}}
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-center mt-6 space-x-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Guardar
                            </button>
                            <a href="{{ route('ubicaciones.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </button>
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
