@extends('template')

@section('title', 'Ubicaciones de Productos')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Ubicaciones de Productos</h1>
            <nav class="breadcrumb mb-6">
                <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                <div class="breadcrumb-item"><a href="{{ route('ubicaciones.index') }}">Ubicaciones</a></div>
                <div class="breadcrumb-item active">Ubicaciones de Productos</div>
            </nav>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Gestión de Ubicaciones de Productos
                </div>
                <div class="card-body">
                    <p class="text-gray-700">
                        Aquí se mostrará la interfaz para gestionar las ubicaciones de los productos.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
@endpush
