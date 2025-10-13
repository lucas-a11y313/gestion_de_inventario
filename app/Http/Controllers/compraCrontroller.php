<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompraRequest;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\Proveedore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class compraCrontroller extends Controller
{
    function __construct()  {
        $this->middleware('permission:ver-compra|crear-compra|mostrar-compra|eliminar-compra',['only' => ['index']]);
        $this->middleware('permission:crear-compra',['only' => ['create','store']]);
        $this->middleware('permission:mostrar-compra',['only' => ['show']]);
        $this->middleware('permission:eliminar-compra',['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('comprobante','proveedore.persona')->where('estado',1)->latest()->get();
        
        return view('compra.index',compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedore::with('persona')->whereHas('persona',function($query) {
            $query->where('estado',1);
        })->get();//El método whereHas() en Laravel se usa para filtrar registros según una relación, asegurando que solo se devuelvan aquellos registros cuyo modelo relacionado cumpla con una condición específica.
        
        $comprobantes = Comprobante::all();//Utilizo all() cuando tengo muy pocos registros(menos de 50 registros)
        $productos = Producto::where('estado',1)->get();
        
        return view('compra.create',compact('proveedores','comprobantes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
    {
        //dd($request->validated());
        try {
            DB::beginTransaction();

            //Llenar la tabla compras
            $compra = Compra::create($request->validated());
            
            //Llenar tabla compra_producto
            //1. recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioCompra = $request->get('arraypreciocompra');
            $arrayPrecioVenta = $request->get('arrayprecioventa');

            //2. Realizar el llenado
            $sizeArray = count($arrayProducto_id);
            $cont = 0;
            while($cont < $sizeArray) {
                
                $compra->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_compra' => $arrayPrecioCompra[$cont],
                        'precio_venta' => $arrayPrecioVenta[$cont]
                    ]
                ]);//syncWithoutDetaching es un metodo que te permite añadir registros a la tabla pivote

                //3. Actualizar el stock sumando lo que tiene actualmente más con la nueva cantidad que compró
                $producto = Producto::find($arrayProducto_id[$cont]);//Buscamos el producto al cual le vamos a actualizar el stock
                $stockActual = $producto->stock;//Obtenemos su stock actual 
                $stockNuevo = intval($arrayCantidad[$cont]);//intval() se utiliza para convertir el valor de $arrayCantidad en un entero
                
                DB::table('productos')->where('id',$producto->id)->update([
                    'stock' => $stockActual + $stockNuevo
                ]);

                $cont++;
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            //dd($e->getMessage());
        }

        return redirect()->route('compras.index')->with('success','Compra exitosa');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load('comprobante','proveedore.persona','productos');

        //dd($compra->productos);
        //dd($compra);
        return view('compra.show',compact('compra'));
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
        Compra::where('id',$id)->update(['estado' => 0]);

        return redirect()->route('compras.index')->with('success','Compra eliminada');
    }

    public function eliminadas()
    {
        $compras = Compra::with(['comprobante','proveedore.persona'])
            ->where('estado', 0)
            ->latest()
            ->get();

        return view('compra.compras_eliminadas', compact('compras'));
    }
}
