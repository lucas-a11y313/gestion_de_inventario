<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ficha de Bien Patrimonial (BP) - {{ $inventariobp->bp }}</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
      margin: 20px;
    }
    .header {
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #2563eb;
      overflow: hidden;
      min-height: 70px;
    }
    .header-logo {
      float: left;
      width: 150px;
      height: 70px;
      margin-right: 20px;
    }
    .header-logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .header-logo svg {
      width: 60px;
      height: 60px;
    }
    .header-text {
      padding-top: 15px;
    }
    .header-title {
      font-size: 18px;
      font-weight: bold;
      color: #2563eb;
      margin: 0 0 5px 0;
    }
    .header-subtitle {
      font-size: 11px;
      color: #666;
      margin: 0;
    }
    h1 {
      text-align: center;
      margin-bottom: 1rem;
      margin-top: 1rem;
      color: #333;
      font-size: 16px;
    }
    .info-section {
      margin-bottom: 1.5rem;
      background-color: #f9f9f9;
      padding: 10px;
      border-radius: 5px;
    }
    .info-row {
      display: flex;
      margin-bottom: 8px;
    }
    .info-label {
      font-weight: bold;
      width: 150px;
      color: #555;
    }
    .info-value {
      flex: 1;
      color: #000;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      border: 1px solid #444;
      padding: 8px;
      text-align: left;
    }
    thead {
      background-color: #2563eb;
      color: white;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .footer {
      margin-top: 2rem;
      text-align: center;
      font-size: 10px;
      color: #777;
    }
  </style>
</head>
<body>
  <!-- Encabezado con logo -->
  <div class="header">
    <div class="header-logo">
      @if(file_exists(public_path('img/logo.png')))
        <img src="{{ public_path('img/logo.png') }}" alt="Logo">
      @else
        <!-- Icono por defecto SVG si no hay logo -->
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect width="24" height="24" rx="4" fill="#2563eb"/>
          <path d="M7 12L10 15L17 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      @endif
    </div>
  </div>

  <h1>Ficha de Bien Patrimonial (BP)</h1>

  <div class="info-section">
    <div class="info-row">
      <span class="info-label">N° de BP:</span>
      <span class="info-value">{{ $inventariobp->bp }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Producto:</span>
      <span class="info-value">{{ $inventariobp->producto->nombre }} (Código: {{ $inventariobp->producto->codigo }})</span>
    </div>
    <div class="info-row">
      <span class="info-label">Responsable Actual:</span>
      <span class="info-value">{{ $inventariobp->user->name }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Origen:</span>
      <span class="info-value">{{ $inventariobp->origen ?? 'No especificado' }}</span>
    </div>
  </div>

  <h3 style="margin-top: 1.5rem; color: #333;">Historial de Asignaciones</h3>
  <table>
    <thead>
      <tr>
        <th>Responsable</th>
        <th class="text-center">Fecha de Asignación</th>
        <th>Asignado Por</th>
        <th class="text-center">Fecha de Desasignación</th>
      </tr>
    </thead>
    <tbody>
      @forelse($inventariobp->historialUsuarios as $historial)
        <tr>
          <td>{{ $historial->name }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($historial->pivot->created_at)->format('d/m/Y H:i') }}</td>
          <td>
            @if($historial->pivot->asignado_por)
              {{ App\Models\User::find($historial->pivot->asignado_por)->name ?? 'Usuario no encontrado' }}
            @else
              Sistema
            @endif
          </td>
          <td class="text-center">{{ $historial->pivot->fecha_desasignacion ? \Carbon\Carbon::parse($historial->pivot->fecha_desasignacion)->format('d/m/Y') : 'Asignado' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-center">No hay historial de asignaciones.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    Generado el {{ now()->format('d-m-Y H:i:s') }}
  </div>
</body>
</html>
