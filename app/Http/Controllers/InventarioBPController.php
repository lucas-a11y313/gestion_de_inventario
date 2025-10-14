<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventarioBPRequest;
use App\Http\Requests\UpdateInventarioBPRequest;
use App\Models\InventarioBP;
use App\Models\Producto;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;

class InventarioBPController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-inventarioBP|crear-inventarioBP|editar-inventarioBP|mostrar-inventarioBP', ['only' => ['index']]);
        $this->middleware('permission:crear-inventarioBP', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-inventarioBP', ['only' => ['edit', 'update']]);
        $this->middleware('permission:mostrar-inventarioBP', ['only' => ['show']]);
        $this->middleware('permission:ver-inventarioBP', ['only' => ['pdf']]);
        $this->middleware('permission:mostrar-inventarioBP', ['only' => ['print']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cargar todos los registros de InventarioBP con sus relaciones
        $inventarioBPs = InventarioBP::with(['producto', 'user'])
            ->latest()
            ->get();

        return view('InventarioBP.index', compact('inventarioBPs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener productos tipo BP activos
        $productos = Producto::where('estado', 1)
            ->where('tipo', 'BP')
            ->orderBy('nombre')
            ->get();

        // Obtener usuarios activos
         $usuarios = User::orderBy('name')->get();

        return view('InventarioBP.create', compact('productos', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarioBPRequest $request)
    {
        // Crear el nuevo registro de BP (la validación ya se hizo en el Request)
        $inventarioBP = InventarioBP::create($request->validated());

        // Registrar la asignación inicial en el historial
        $inventarioBP->usuarios()->attach($request->user_id, [
            'asignado_por' => auth()->id(), // El usuario autenticado que creó el BP
            'fecha_desasignacion' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redireccionar con mensaje de éxito
        return redirect()->route('inventariobp.index')
            ->with('success', 'BP creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventarioBP $inventariobp)
    {
        // Cargar el BP con sus relaciones y historial de usuarios
        $inventariobp->load([
            'producto',
            'user',
            'historialUsuarios'
        ]);

        return view('InventarioBP.show', compact('inventariobp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventarioBP $inventariobp)
    {
        // Obtener productos tipo BP activos
        $productos = Producto::where('estado', 1)
            ->where('tipo', 'BP')
            ->orderBy('nombre')
            ->get();

        // Obtener usuarios
        $usuarios = User::orderBy('name')->get();

        return view('InventarioBP.edit', compact('inventariobp', 'productos', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventarioBPRequest $request, InventarioBP $inventariobp)
    {
        // Verificar si cambió el responsable
        $usuarioCambio = $inventariobp->user_id != $request->user_id;

        if ($usuarioCambio) {
            // Desasignar al usuario anterior (marcar fecha_desasignacion)
            $inventariobp->usuarios()
                ->wherePivot('user_id', $inventariobp->user_id)
                ->whereNull('fecha_desasignacion')
                ->updateExistingPivot($inventariobp->user_id, [
                    'fecha_desasignacion' => now()->toDateString(),
                ]);

            // Asignar al nuevo usuario
            $inventariobp->usuarios()->attach($request->user_id, [
                'asignado_por' => auth()->id(),
                'fecha_desasignacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Actualizar el registro de BP (la validación ya se hizo en el Request)
        $inventariobp->update($request->validated());

        // Redireccionar con mensaje de éxito
        return redirect()->route('inventariobp.index')
            ->with('success', 'BP actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get BP data for modal (AJAX endpoint)
     */
    public function getData($id)
    {
        $inventariobp = InventarioBP::with(['producto', 'user'])->findOrFail($id);

        return response()->json([
            'id' => $inventariobp->id,
            'bp' => $inventariobp->bp,
            'codigo' => $inventariobp->producto->codigo,
            'nombre' => $inventariobp->producto->nombre,
            'responsable' => $inventariobp->user->name,
            'origen' => $inventariobp->origen ?? 'N/A',
            'ubicacion' => $inventariobp->ubicacion ?? 'N/A',
            'color' => $inventariobp->color ?? 'N/A',
            'img_path' => $inventariobp->producto->img_path,
            'img_url' => $inventariobp->producto->img_path
                ? asset('storage/productos/' . $inventariobp->producto->img_path)
                : null
        ]);
    }

    /**
     * Generate a PDF for the specified resource.
     *
     * @param  \App\Models\InventarioBP  $inventariobp
     * @return \Illuminate\Http\Response
     */
    public function print(InventarioBP $inventariobp)
    {
        $inventariobp->load(['producto', 'user', 'historialUsuarios']);

        $pdf = Pdf::loadView('InventarioBP.bp_pdf', compact('inventariobp'));

        // Opcional: stream() para mostrar en navegador, download() para forzar descarga
        return $pdf->stream('bp-' . $inventariobp->bp . '.pdf');
    }

    /**
     * Generate a PDF of the BP inventory list.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf()
    {
        $inventarioBPs = InventarioBP::with(['producto', 'user'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('InventarioBP.index_pdf', compact('inventarioBPs'));

        return $pdf->stream('inventario-bp.pdf');
    }
}
