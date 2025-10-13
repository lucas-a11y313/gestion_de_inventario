@extends('template')

@section('title', 'Editar Usuario')

@push('css')
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Editar Usuario</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></div>
                <div class="breadcrumb-item active">Editar usuario</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Usuario
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', ['user' => $user]) }}" method="post">
                        @method('PATCH')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Nombre -->
                            <div class="form-group">
                                <label for="name" class="form-label">Nombre:</label>
                                <input type="text" name="name" id="name" class="form-input"
                                    value="{{ old('name', $user->name) }}" placeholder="Ej: Juan Pérez">
                                <p class="text-sm text-gray-500 mt-1">Ingrese el nombre completo del usuario</p>
                                @error('name')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" class="form-input"
                                    value="{{ old('email', $user->email) }}" placeholder="usuario@ejemplo.com">
                                <p class="text-sm text-gray-500 mt-1">Dirección de correo electrónico</p>
                                @error('email')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="form-group">
                                <label for="password" class="form-label">Nueva Contraseña:</label>
                                <input type="password" name="password" id="password" class="form-input"
                                    placeholder="Dejar en blanco si no desea cambiarla">
                                <p class="text-sm text-gray-500 mt-1">Solo complete si desea cambiar la contraseña</p>
                                @error('password')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div class="form-group">
                                <label for="password_confirm" class="form-label">Confirmar Contraseña:</label>
                                <input type="password" name="password_confirm" id="password_confirm" class="form-input"
                                    placeholder="Repita la nueva contraseña">
                                <p class="text-sm text-gray-500 mt-1">Debe coincidir con la nueva contraseña</p>
                                @error('password_confirm')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Rol -->
                            <div class="form-group md:col-span-2">
                                <label for="role" class="form-label">Seleccionar Rol:</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="">Seleccione un rol</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->name }}"
                                            {{ (in_array($item->name, $user->roles->pluck('name')->toArray()) || old('role') == $item->name) ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Rol actual: <strong>{{ $user->roles->first()->name ?? 'Sin rol' }}</strong></p>
                                @error('role')
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
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
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
