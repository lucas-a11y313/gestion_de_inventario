@extends('template')

@section('title', 'Crear Inventario Insumos')

@push('css')
    <style>
        #razon {
            resize: none;
        }
    </style>
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Crear Insumo</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('inventarioinsumos.index') }}">Inventario de Insumos</a></div>
                <div class="breadcrumb-item active">Crear Insumo</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Insumo
                </div>
                <div class="card-body">
                    <form action="#" method="post">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Código del Producto -->
                            <div class="form-group">
                                <label for="codigo" class="form-label">Código del Producto:</label>
                                <select name="codigo" id="codigo" class="form-select">
                                    <option value="">Seleccione el código</option>
                                    <option value="INS001" {{ old('codigo') == 'INS001' ? 'selected' : '' }}>INS001</option>
                                    <option value="INS002" {{ old('codigo') == 'INS002' ? 'selected' : '' }}>INS002</option>
                                    <option value="INS003" {{ old('codigo') == 'INS003' ? 'selected' : '' }}>INS003</option>
                                </select>
                                @error('codigo')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Nombre del Producto -->
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre del Producto:</label>
                                <select name="nombre" id="nombre" class="form-select">
                                    <option value="">Seleccione el producto</option>
                                    <option value="Guantes de látex" {{ old('nombre') == 'Guantes de látex' ? 'selected' : '' }}>Guantes de látex</option>
                                    <option value="Mascarillas N95" {{ old('nombre') == 'Mascarillas N95' ? 'selected' : '' }}>Mascarillas N95</option>
                                    <option value="Jeringas desechables" {{ old('nombre') == 'Jeringas desechables' ? 'selected' : '' }}>Jeringas desechables</option>
                                </select>
                                @error('nombre')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Cantidad -->
                            <div class="form-group">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <select name="cantidad" id="cantidad" class="form-select">
                                    <option value="">Seleccione la cantidad</option>
                                    <option value="1" {{ old('cantidad') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="5" {{ old('cantidad') == '5' ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ old('cantidad') == '10' ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ old('cantidad') == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ old('cantidad') == '50' ? 'selected' : '' }}>50</option>
                                </select>
                                @error('cantidad')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Retirado por -->
                            <div class="form-group">
                                <label for="retirado_por" class="form-label">Retirado por:</label>
                                <input type="text" name="retirado_por" id="retirado_por" class="form-input"
                                    value="{{ old('retirado_por') }}" placeholder="Ej: Dr. García">
                                @error('retirado_por')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Razón del retiro -->
                            <div class="form-group md:col-span-2">
                                <label for="razon" class="form-label">Razón del retiro:</label>
                                <textarea name="razon" id="razon" rows="3" class="form-input"
                                    placeholder="Ej: Uso en consulta médica">{{ old('razon') }}</textarea>
                                @error('razon')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div class="form-group">
                                <label for="estado" class="form-label">Estado:</label>
                                <select name="estado" id="estado" class="form-select">
                                    <option value="">Seleccione el estado</option>
                                    <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Retirado" {{ old('estado') == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                                    <option value="Vencido" {{ old('estado') == 'Vencido' ? 'selected' : '' }}>Vencido</option>
                                </select>
                                @error('estado')
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
                            <a href="{{ route('inventarioinsumos.index') }}" class="btn btn-secondary">
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
