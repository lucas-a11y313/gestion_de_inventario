<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioInsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Producto::where('tipo', 'Insumo')
            ->with([
                'adquisiciones' => function($query) {
                    $query->withPivot('precio_compra');
                },
                'compras' => function($query) {
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
        return view('InventarioInsumos.edit');
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
}
