@extends('template')

@section('title', 'Crear productos')

@push('css')
    <style>
        #descripcion {
            resize: none;
        }
    </style>
@endpush

@section('content')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Crear Producto</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></div>
                <div class="breadcrumb-item active">Crear producto</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Producto
                </div>
                <div class="card-body">
                    <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Código -->
                            <div class="form-group">
                                <label for="codigo" class="form-label">Código:</label>
                                <input type="text" name="codigo" id="codigo" class="form-input"
                                    value="{{ old('codigo') }}">
                                @error('codigo')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Nombre -->
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" name="nombre" id="nombre" class="form-input"
                                    value="{{ old('nombre') }}">
                                @error('nombre')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="form-group md:col-span-2">
                                <label for="descripcion" class="form-label">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" rows="3" class="form-input">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Fecha de vencimiento -->
                            <div class="form-group">
                                <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento:</label>
                                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-input"
                                    value="{{ old('fecha_vencimiento') }}">
                                @error('fecha_vencimiento')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Imagen -->
                            <div class="form-group">
                                <label for="img_path" class="form-label">Imagen:</label>
                                <input type="file" name="img_path" id="img_path" class="form-input" accept="image/*"
                                    value="{{ old('img_path') }}">
                                @error('img_path')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div class="form-group">
                                <label for="tipo" class="form-label">Tipo:</label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="">Seleccione el tipo</option>
                                    <option value="BP" {{ old('tipo') == 'BP' ? 'selected' : '' }}>BP</option>
                                    <option value="Insumo" {{ old('tipo') == 'Insumo' ? 'selected' : '' }}>Insumo</option>
                                </select>
                                @error('tipo')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Marca -->
                            <div class="form-group">
                                <label for="marca_id" class="form-label">Marca:</label>
                                <select name="marca_id" id="marca_id" class="form-select">
                                    <option value="">Seleccione una marca</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>
                                            {{ $marca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('marca_id')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Categorías -->
                            <div class="form-group">
                                <label for="categorias" class="form-label">Categorías:</label>
                                <select name="categorias[]" id="categorias" class="form-select" multiple>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" 
                                            {{ in_array($categoria->id, old('categorias', [])) ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    
                                    
                                    @endforeach
                                </select>
                                @error('categorias')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>



                            <!-- Sugerencia -->
                            <div class="form-group">
                                <label for="sugerencia" class="form-label">Sugerencia:</label>
                                <textarea name="sugerencia" id="sugerencia" class="form-input" rows="3" placeholder="Ej: Desechar, Donar, Uso en el laboratorio">{{ old('sugerencia') }}</textarea>
                                @error('sugerencia')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Ubicaciones -->
                            <div class="form-group">
                                <label for="ubicaciones" class="form-label">Ubicaciones:</label>
                                <select name="ubicaciones[]" id="ubicaciones" class="form-select" multiple>
                                    @foreach ($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->id }}"
                                            {{ in_array($ubicacion->id, old('ubicaciones', [])) ? 'selected' : '' }}>
                                            {{ $ubicacion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ubicaciones')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                        </div>

                        <!-- Botones -->
                        <div class="flex justify-center mt-6 space-x-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Guardar
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
        </div>
    </div>

@endsection
