<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Models\Solicitud;
use App\Models\Producto;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class SolicitudController extends Controller
{
    function __construct()  {
        $this->middleware('permission:ver-solicitud|crear-solicitud|mostrar-solicitud|eliminar-solicitud',['only' => ['index']]);
        $this->middleware('permission:crear-solicitud',['only' => ['create','store']]);
        $this->middleware('permission:mostrar-solicitud',['only' => ['show']]);
        $this->middleware('permission:eliminar-solicitud',['only' => ['destroy','restore']]);
        $this->middleware('permission:ver-solicitud',['only' => ['eliminadas']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $solicitudes = Solicitud::with('user')->where('estado',1)->latest()->get();

        return view('solicitudes.index',compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $productos = Producto::where('estado',1)
            ->where('tipo', 'Insumo') // Solo productos de tipo Insumo
            ->with(['adquisiciones' => function($query) {
                $query->orderBy('adquisicion_producto.created_at', 'desc')->limit(1);
            }])
            ->get()
            ->map(function($producto) {
                // Obtener el precio de compra más reciente de adquisiciones
                $ultimaAdquisicion = $producto->adquisiciones->first();
                $producto->ultimo_precio_compra = $ultimaAdquisicion ? $ultimaAdquisicion->pivot->precio_compra : 0;
                return $producto;
            });

        return view('solicitudes.create',compact('users', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSolicitudRequest $request)
    {
        try {
            DB::beginTransaction();

            //Llenar la tabla solicitudes
            $solicitud = Solicitud::create($request->validated());

            //Llenar tabla producto_solicitud
            //1. recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioCompra = $request->get('arraypreciocompra');

            //2. Realizar el llenado
            $sizeArray = count($arrayProducto_id);
            $cont = 0;
            while($cont < $sizeArray) {

                $solicitud->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_compra' => $arrayPrecioCompra[$cont]
                    ]
                ]);

                //3. Actualizar el stock restando la cantidad solicitada
                $producto = Producto::find($arrayProducto_id[$cont]);
                $stockActual = $producto->stock;
                $cantidadSolicitada = intval($arrayCantidad[$cont]);

                // Restar del stock la cantidad solicitada
                DB::table('productos')->where('id',$producto->id)->update([
                    'stock' => $stockActual - $cantidadSolicitada
                ]);

                $cont++;
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('solicitudes.index')->with('success','Solicitud exitosa');
    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitud $solicitude)
    {
        $solicitude->load('user','productos');

        return view('solicitudes.show',['solicitud' => $solicitude]);
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
        Solicitud::where('id',$id)->update(['estado' => 0]);

        return redirect()->route('solicitudes.index')->with('success','Solicitud eliminada');
    }

    public function eliminadas()
    {
        $solicitudes = Solicitud::with(['user'])
            ->where('estado', 0)
            ->latest()
            ->get();

        return view('solicitudes.solicitudes_eliminadas', compact('solicitudes'));
    }

    public function restore(string $id)
    {
        Solicitud::where('id', $id)->update(['estado' => 1]);

        return redirect()->route('solicitudes.eliminadas')->with('success', 'Solicitud restaurada exitosamente');
    }

    /**
     * Generate PDF for solicitud
     */
    public function print(Solicitud $solicitude)
    {
        // Cargar las relaciones necesarias
        $solicitude->load('user', 'productos');

        // Generar el PDF
        $pdf = Pdf::loadView('solicitudes.solicitud_pdf', ['solicitud' => $solicitude])
            ->setPaper('a4', 'portrait');

        // Retornar el PDF para visualización en el navegador
        return $pdf->stream('solicitud_' . $solicitude->id . '_' . now()->format('Ymd') . '.pdf');

        // Alternativa: descarga directa
        // return $pdf->download('solicitud_' . $solicitude->id . '_' . now()->format('Ymd') . '.pdf');
    }
}
