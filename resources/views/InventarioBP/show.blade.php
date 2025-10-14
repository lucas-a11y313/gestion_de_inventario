@extends('template')

@section('title', 'Detalle de BP')

@section('content')
    @can('mostrar-inventarioBP')
        <div class="px-4 py-6">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 text-center mb-6">Detalle del BP</h1>
                <nav class="breadcrumb mb-6">
                    <div class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('inventariobp.index') }}">Inventario de BP</a></div>
                    <div class="breadcrumb-item active">{{ $inventariobp->bp }}</div>
                </nav>

                <div>
                    <a href="{{ route('inventariobp.print', $inventariobp) }}" class="btn btn-danger" target="_blank">
                        <i class="fas fa-print"></i> Imprimir PDF
                    </a>
                </div>

                <!-- Información Principal -->
                <div class="card mb-6">
                    <div class="card-header">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información del BP
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-semibold text-gray-700">BP:</label>
                                <p class="text-gray-900">{{ $inventariobp->bp }}</p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Código del Producto:</label>
                                <p class="text-gray-900">{{ $inventariobp->producto?->codigo ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Nombre del Producto:</label>
                                <p class="text-gray-900">{{ $inventariobp->producto?->nombre ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Responsable Actual:</label>
                                <p class="text-gray-900">{{ $inventariobp->user?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Origen:</label>
                                <p class="text-gray-900">{{ $inventariobp->origen ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700">Ubicación:</label>
                                <p class="text-gray-900">{{ $inventariobp->producto?->ubicacion ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Responsables -->
                <div class="card mb-6">
                    <div class="card-header">
                        <i class="fas fa-history mr-2"></i>
                        Historial de Responsables
                    </div>
                    <div class="card-body">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Responsable
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Asignado Por
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha Asignación
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha Desasignación
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Duración
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($inventariobp->historialUsuarios as $usuario)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $usuario->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $usuario->pivot->asignado_por ? \App\Models\User::find($usuario->pivot->asignado_por)?->name : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $usuario->pivot->created_at ? $usuario->pivot->created_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $usuario->pivot->fecha_desasignacion ? \Carbon\Carbon::parse($usuario->pivot->fecha_desasignacion)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($usuario->pivot->fecha_desasignacion)
                                                    {{ \Carbon\Carbon::parse($usuario->pivot->created_at)->diffInDays(\Carbon\Carbon::parse($usuario->pivot->fecha_desasignacion)) }} días
                                                @else
                                                    {{ \Carbon\Carbon::parse($usuario->pivot->created_at)->diffInDays(now()) }} días (actual)
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($usuario->pivot->fecha_desasignacion)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Finalizado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Activo
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No hay historial de responsables
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-center gap-3">
                    @can('editar-inventarioBP')
                        <a href="{{ route('inventariobp.edit', $inventariobp) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                    @endcan
                    <a href="{{ route('inventariobp.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="px-4 py-6">
            <div class="max-w-4xl mx-auto">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    No tienes permiso para ver detalles de BP.
                </div>
            </div>
        </div>
    @endcan
@endsection
