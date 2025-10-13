@extends('template')

@section('title', 'Editar Inventario BP')

@push('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #374151;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }
    </style>
@endpush

@section('content')
    @can('editar-inventarioBP')
        <div class="px-4 py-6">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Editar BP</h1>
                <nav class="breadcrumb mb-6">
                    <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('inventariobp.index') }}">Inventario de BP</a></div>
                    <div class="breadcrumb-item active">Editar BP</div>
                </nav>

                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-edit mr-2"></i>
                        Editar BP
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventariobp.update', $inventariobp) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- BP -->
                                <div class="form-group">
                                    <label for="bp" class="form-label">BP:</label>
                                    <input type="text" name="bp" id="bp" class="form-input" placeholder="Ej: BP-001" value="{{ old('bp', $inventariobp->bp) }}">
                                    @error('bp')
                                        <small class="form-error">{{ '*' . $message }}</small>
                                    @enderror
                                </div>

                                <!-- Nombre del producto (con Select2) -->
                                <div class="form-group">
                                    <label for="producto_id" class="form-label">Nombre del producto:</label>
                                    <select name="producto_id" id="producto_id" class="form-select">
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" {{ old('producto_id', $inventariobp->producto_id) == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->codigo }} - {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('producto_id')
                                        <small class="form-error">{{ '*' . $message }}</small>
                                    @enderror
                                </div>

                                <!-- Responsable -->
                                <div class="form-group">
                                    <label for="user_id" class="form-label">Responsable:</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="">Seleccione un responsable</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" {{ old('user_id', $inventariobp->user_id) == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <small class="form-error">{{ '*' . $message }}</small>
                                    @enderror
                                </div>

                                <!-- Origen -->
                                <div class="form-group">
                                    <label for="origen" class="form-label">Origen:</label>
                                    <select name="origen" id="origen" class="form-select">
                                        <option value="">Seleccione el origen</option>
                                        <option value="Convenio" {{ old('origen', $inventariobp->origen) == 'Convenio' ? 'selected' : '' }}>Convenio</option>
                                        <option value="Donacion" {{ old('origen', $inventariobp->origen) == 'Donación' ? 'selected' : '' }}>Donación</option>
                                        <option value="Prestamo" {{ old('origen', $inventariobp->origen) == 'Préstamo' ? 'selected' : '' }}>Préstamo</option>
                                        <option value="Proyecto" {{ old('origen', $inventariobp->origen) == 'Proyecto' ? 'selected' : '' }}>Proyecto</option>
                                    </select>
                                    @error('origen')
                                        <small class="form-error">{{ '*' . $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <!-- Botones -->
                            <div class="flex justify-center gap-3 mt-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar
                                </button>
                                <a href="{{ route('inventariobp.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="px-4 py-6">
            <div class="max-w-4xl mx-auto">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    No tienes permiso para editar BP.
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2 en el select de productos
            $('#producto_id').select2({
                placeholder: 'Busque un producto aquí...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron productos";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
        });
    </script>
@endpush
