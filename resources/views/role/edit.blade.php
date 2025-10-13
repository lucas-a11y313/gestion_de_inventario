@extends('template')

@section('title', 'Editar Rol')

@push('css')
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Editar Rol</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></div>
                <div class="breadcrumb-item active">Editar rol</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Rol
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.update', ['role' => $role]) }}" method="post">
                        @method('PATCH')
                        @csrf
                        <div class="grid grid-cols-1 gap-6">

                            <!-- Nombre del Rol -->
                            <div class="form-group">
                                <label for="name" class="form-label">Nombre del Rol:</label>
                                <input type="text" name="name" id="name" class="form-input"
                                    value="{{ old('name', $role->name) }}" placeholder="Ej: Administrador">
                                <p class="text-sm text-gray-500 mt-1">Rol actual: <strong>{{ $role->name }}</strong></p>
                                @error('name')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Permisos -->
                            <div class="form-group">
                                <label class="form-label">Permisos para el rol:</label>
                                <p class="text-sm text-gray-500 mb-4">
                                    Permisos actuales: <strong>{{ $role->permissions->count() }}</strong> de {{ $permisos->count() }} disponibles
                                </p>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach ($permisos as $item)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="permission[]" id="{{ $item->id }}"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                    value="{{ $item->id }}"
                                                    {{ (in_array($item->id, old('permission', $role->permissions->pluck('id')->toArray()))) ? 'checked' : '' }}>
                                                <label for="{{ $item->id }}" class="ml-3 text-sm font-medium text-gray-700">
                                                    {{ $item->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('permission')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-center mt-8 space-x-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
