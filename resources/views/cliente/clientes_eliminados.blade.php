@extends('template')

@section('title', 'Clientes Eliminados')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
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
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Clientes Eliminados</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></div>
                <div class="breadcrumb-item active">Clientes eliminados</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('clientes.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-table mr-2"></i>Tabla clientes
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-gray-700 font-medium">Tabla clientes eliminados</span>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nro. Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($clientes as $cliente)
                            <tr>
                                <td class="px-6 py-4">{{ $cliente->persona->razon_social }}</td>
                                <td class="px-6 py-4">{{ $cliente->persona->direccion }}</td>
                                <td class="px-6 py-4">{{ $cliente->persona->documento->tipo_documento }}</td>
                                <td class="px-6 py-4">{{ $cliente->persona->numero_documento }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Eliminado</span>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('clientes.destroy', ['cliente' => $cliente->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-undo mr-1"></i>Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
