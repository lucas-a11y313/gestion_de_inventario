<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Producto;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InventarioInsumosController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Producto::where('tipo', 'Insumo')
            ->with([
                'adquisiciones' => function($query) {
                    $query->withPivot('precio_compra');
                }
            ])
            ->get();

        return view('InventarioInsumos.index', compact('insumos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('InventarioInsumos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get Insumo data for modal (AJAX endpoint)
     */
    public function getData($id)
    {
        $producto = Producto::where('tipo', 'Insumo')->findOrFail($id);

        return response()->json([
            'id' => $producto->id,
            'codigo' => $producto->codigo,
            'nombre' => $producto->nombre,
            'stock' => $producto->stock,
            'img_path' => $producto->img_path,
            'img_url' => $producto->img_path
                ? asset('storage/productos/' . $producto->img_path)
                : null
        ]);
    }

    /**
     * Display the origin of supplies (insumos).
     */
    public function origenInsumos()
    {
        // Get all products of type "Insumo" with their acquisitions and nested relations
        $origenes = Producto::where('tipo', 'Insumo')
            ->with(['adquisiciones.proveedore.persona'])
            ->get()
            ->flatMap(function ($producto) {
                // For each product, get all its acquisitions
                return $producto->adquisiciones->map(function ($adquisicion) use ($producto) {
                    // Get proveedor name from the nested persona relationship
                    $proveedorNombre = 'N/A';
                    if ($adquisicion->proveedore && $adquisicion->proveedore->persona) {
                        $proveedorNombre = $adquisicion->proveedore->persona->razon_social
                            ?? $adquisicion->proveedore->persona->nombre
                            ?? 'N/A';
                    }

                    return [
                        'producto_codigo' => $producto->codigo,
                        'producto_nombre' => $producto->nombre,
                        'cantidad' => $adquisicion->pivot->cantidad ?? 0,
                        'precio_compra' => $adquisicion->pivot->precio_compra ?? 0,
                        'tipo_adquisicion' => $adquisicion->tipo_adquisicion ?? 'N/A',
                        'fecha' => $adquisicion->fecha_hora,
                        'proveedor' => $proveedorNombre
                    ];
                });
            });

        return view('InventarioInsumos.origen_insumos', compact('origenes'));
    }

    public function insumosRetirados()
    {
        $solicitudes = Solicitud::where('tipo_solicitud', 'retiro')
            ->with(['user', 'productos' => function ($query) {
                $query->where('tipo', 'Insumo');
            }])
            ->whereHas('productos', function ($query) {
                $query->where('tipo', 'Insumo');
            })
            ->get();

        return view('InventarioInsumos.insumos_retirados', compact('solicitudes'));
    }

    public function insumosPrestados()
    {
        // Obtener todas las solicitudes de préstamo que tienen al menos un insumo sin devolver
        $solicitudes = Solicitud::where('tipo_solicitud', 'prestamo')
            ->whereHas('productos', function ($query) {
                $query->where('productos.tipo', 'Insumo')
                    ->whereNull('producto_solicitud.fecha_devolucion');
            })
            ->with(['user', 'productos' => function ($query) {
                // Solo cargar los insumos que no han sido devueltos
                $query->where('productos.tipo', 'Insumo')
                    ->whereNull('producto_solicitud.fecha_devolucion');
            }])
            ->get();

        return view('InventarioInsumos.insumos_prestados', compact('solicitudes'));
    }

    public function devolverInsumo(Request $request, $solicitud_id, $producto_id)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitud_id);
            $producto = Producto::findOrFail($producto_id);

            // Update stock
            $pivot = $solicitud->productos()->where('producto_id', $producto->id)->first()->pivot;
            $cantidad_prestada = $pivot->cantidad;
            $producto->stock += $cantidad_prestada;
            $producto->save();

            // Update pivot table with return date
            $solicitud->productos()->updateExistingPivot($producto->id, [
                'fecha_devolucion' => now(),
            ]);

            return redirect()->route('insumos.prestados')->with('success', 'Insumo devuelto correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('insumos.prestados')->with('error', 'Hubo un error al devolver el insumo: ' . $e->getMessage());
        }
    }

    public function pdf()
    {
        $insumos = Producto::where('tipo', 'Insumo')
            ->with([
                'adquisiciones' => function($query) {
                    $query->withPivot('precio_compra');
                }
            ])
            ->get();

        $pdf = Pdf::loadView('InventarioInsumos.index_pdf', compact('insumos'));
        return $pdf->stream('reporte_inventario_insumos.pdf');
    }
}
