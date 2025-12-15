<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdquisicionRequest;
use App\Models\Adquisicion;
use App\Models\Producto;
use App\Models\Proveedore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class AdquisicionController extends Controller
{
    function __construct()  {
        $this->middleware('permission:ver-adquisicion|crear-adquisicion|mostrar-adquisicion|eliminar-adquisicion',['only' => ['index']]);
        $this->middleware('permission:crear-adquisicion',['only' => ['create','store']]);
        $this->middleware('permission:mostrar-adquisicion',['only' => ['show']]);
        $this->middleware('permission:eliminar-adquisicion',['only' => ['destroy', 'restaurar']]);
        $this->middleware('permission:ver-adquisicion',['only' => ['eliminadas']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adquisiciones = Adquisicion::with('proveedore.persona')->where('estado',1)->latest()->get();

        return view('adquisicion.index',compact('adquisiciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedore::with('persona')->whereHas('persona',function($query) {
            $query->where('estado',1);
        })->get();

        $productos = Producto::where('estado',1)->get();

        return view('adquisicion.create',compact('proveedores', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdquisicionRequest $request)
    {
        try {
            DB::beginTransaction();

            //Llenar la tabla adquisiciones
            $adquisicion = Adquisicion::create($request->validated());

            //Llenar tabla adquisicion_producto
            //1. recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioCompra = $request->get('arraypreciocompra');

            //2. Realizar el llenado
            $sizeArray = count($arrayProducto_id);
            $cont = 0;
            while($cont < $sizeArray) {

                $adquisicion->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_compra' => $arrayPrecioCompra[$cont]
                    ]
                ]);

                //3. Actualizar el stock sumando lo que tiene actualmente más con la nueva cantidad que compró
                $producto = Producto::find($arrayProducto_id[$cont]);
                $stockActual = $producto->stock;
                $stockNuevo = intval($arrayCantidad[$cont]);

                DB::table('productos')->where('id',$producto->id)->update([
                    'stock' => $stockActual + $stockNuevo
                ]);

                $cont++;
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('adquisiciones.create')->with('error', 'Error al crear la adquisición: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('adquisiciones.index')->with('success','Adquisición exitosa');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adquisicion $adquisicion)
    {
        $adquisicion->load('proveedore.persona','productos');

        return view('adquisicion.show',compact('adquisicion'));
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
        Adquisicion::where('id',$id)->update(['estado' => 0]);

        return redirect()->route('adquisiciones.index')->with('success','Adquisición eliminada');
    }

    public function eliminadas()
    {
        $adquisiciones = Adquisicion::with(['proveedore.persona'])
            ->where('estado', 0)
            ->latest()
            ->get();

        return view('adquisicion.adquisiciones_eliminadas', compact('adquisiciones'));
    }

    public function restaurar(string $id)
    {
        Adquisicion::where('id', $id)->update(['estado' => 1]);

        return redirect()->route('adquisiciones.eliminadas')->with('success', 'Adquisición restaurada exitosamente');
    }

    /**
     * Generate PDF for adquisicion
     */
    public function print(Adquisicion $adquisicione)
    {
        // Cargar las relaciones necesarias
        $adquisicione->load('proveedore.persona', 'productos');

        // Generar el PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('adquisicion.adquisicion_pdf', ['adquisicion' => $adquisicione])
            ->setPaper('a4', 'portrait');

        // Retornar el PDF para visualización en el navegador
        return $pdf->stream('adquisicion_' . $adquisicione->id . '_' . now()->format('Ymd') . '.pdf');

        // Alternativa: descarga directa
        // return $pdf->download('adquisicion_' . $adquisicione->id . '_' . now()->format('Ymd') . '.pdf');
    }
}
