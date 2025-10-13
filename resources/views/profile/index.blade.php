@extends('template')

@section('title','Perfil')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}"
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        </script>
    @endif

    <div class="px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Mi Perfil</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item active">Mi Perfil</div>
            </nav>

            <!-- Información del Usuario -->
            <div class="bg-white rounded-lg shadow-sm border mb-6 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Administrador del Sistema
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario de Actualización -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit mr-2"></i>
                    Actualizar Información Personal
                </div>
                <div class="card-body">
                    <!-- Mensajes de Error -->
                    @if ($errors->any())
                        <div class="mb-6">
                            @foreach ($errors->all() as $error)
                                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('profile.update', ['profile' => $user]) }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre Completo -->
                            <div class="form-group md:col-span-2">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user mr-2 text-gray-600"></i>
                                    Nombre Completo
                                </label>
                                <input type="text" name="name" id="name" class="form-input"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="Ingrese su nombre completo">
                                @error('name')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group md:col-span-2">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope mr-2 text-gray-600"></i>
                                    Correo Electrónico
                                </label>
                                <input type="email" name="email" id="email" class="form-input"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="usuario@ejemplo.com">
                                @error('email')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!-- Nueva Contraseña -->
                            <div class="form-group md:col-span-2">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock mr-2 text-gray-600"></i>
                                    Nueva Contraseña
                                </label>
                                <input type="password" name="password" id="password" class="form-input"
                                    placeholder="Dejar en blanco si no desea cambiarla">
                                <p class="text-sm text-gray-500 mt-1">
                                    La contraseña debe tener al menos 8 caracteres
                                </p>
                                @error('password')
                                    <small class="form-error">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-center mt-8 space-x-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Cambios
                            </button>
                            <a href="{{ route('panel') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Actividad Reciente -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Actividad Reciente
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-sign-in-alt mr-3 text-green-500"></i>
                            Último acceso: Hoy
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-edit mr-3 text-blue-500"></i>
                            Perfil actualizado: Hace 2 días
                        </div>
                    </div>
                </div>

                <!-- Configuración de Seguridad -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-shield-alt mr-2 text-green-600"></i>
                        Seguridad
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle mr-3 text-green-500"></i>
                            Cuenta verificada
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-key mr-3 text-blue-500"></i>
                            Contraseña segura
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush