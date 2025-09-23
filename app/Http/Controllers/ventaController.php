<?php

namespace App\Http\Controllers;
                                    
use App\Http\Requests\StoreVentaRequest;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;


class ventaController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta',['only' => ['index']]);
        $this->middleware('permission:crear-venta',['only' => ['create','store']]);
        $this->middleware('permission:mostrar-venta',['only' => ['show']]);
        $this->middleware('permission:eliminar-venta',['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = Venta::with('comprobante','cliente.persona','user')->where('estado',1)->latest()->get();

        return view('venta.index',compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subquery = DB::table('compra_producto')->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))->groupBy('producto_id');
        //return $subquery;
        
        $productos = Producto::join('compra_producto as cpr', function ($join) use ($subquery) {
            $join->on('cpr.producto_id', '=', 'productos.id')->whereIn('cpr.created_at', function ($query) use ($subquery) {
                $query->select('max_created_at')->fromSub($subquery,'subquery')->whereRaw('subquery.producto_id = cpr.producto_id');
            });
        })->select('productos.nombre', 'productos.id', 'productos.stock','cpr.precio_venta')->where('productos.estado',1)->where('productos.stock', '>',0)->get();
        
        $comprobantes = Comprobante::all();//Utilizo all() cuando tengo muy pocos registros(menos de 50 registros)
        
        $clientes = Cliente::with('persona')->whereHas('persona',function($query){
            $query->where('estado',1);
        })->get();//El método whereHas() en Laravel se usa para filtrar registros según una relación, asegurando que solo se devuelvan aquellos registros cuyo modelo relacionado cumpla con una condición específica.
        
        return view('venta.create', compact('productos','comprobantes','clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request)
    {
        //dd($request);
        try {
            DB::beginTransaction();
            //Llenar la tabla ventas
            $venta = Venta::create($request->validated());

            //Llenar la tabla producto_venta
            //1. Recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioVenta = $request->get('arrayprecioventa');
            $arrayDescuento = $request->get('arraydescuento');

            //2. Realizar el llenado 
            $sizeArray = count($arrayProducto_id);
            $cont = 0;
            
            while($cont < $sizeArray) {
                
                $venta->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_venta' => $arrayPrecioVenta[$cont],
                        'descuento' => $arrayDescuento[$cont]
                    ]
                ]);//syncWithoutDetaching es un metodo que te permite añadir registros a la tabla pivote

                //3. Actualizar el stock restando lo que tiene actualmente menos la cantidad que vendió
                $producto = Producto::find($arrayProducto_id[$cont]);//Buscamos el producto al cual le vamos a actualizar el stock
                $stockActual = $producto->stock;//Obtenemos el stock actual.
                $stockNuevo = intval($arrayCantidad[$cont]);//intval() se utiliza para convertir el valor de $arrayCantidad en un entero

                //Actualizar el stock restando el stock actual - el stocknuevo
                DB::table('productos')->where('id', $producto->id)->update([
                    'stock' => $stockActual - $stockNuevo
                ]);
                
                $cont++;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }

        return redirect()->route('ventas.index')->with('success','Venta exitosa');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $venta->load('comprobante', 'cliente.persona', 'user');
        
        return view('venta.show', compact('venta'));
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
        Venta::where('id',$id)->update(['estado' => 0]);
        
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada');
    }

    /**
     * Show the printable ticket.
     */
    public function print(Venta $venta)
    {
        // Carga de relaciones necesarias
        $venta->load('comprobante', 'cliente.persona', 'user', 'productos');
        //dd($venta);
        // Devuelve la vista “limpia” para impresión
        return view('venta.print', compact('venta'));
        
    }

}
