<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Marca;

use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;



class productoController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-producto|crear-producto|editar-producto|eliminar-producto',['only' => ['index']]);
        $this->middleware('permission:crear-producto',['only' => ['create','store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-producto', ['only'=> ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['categorias.caracteristica','marca.caracteristica'])->latest()->get();

        return view('producto.index',compact('productos'));//compact('productos'): Este método convierte la variable $productos en un array asociativo, usar compact('productos') es lo mismo que ['productos' => $productos]
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marcas = Marca::join('caracteristicas as c','marcas.caracteristica_id','=','c.id')
        ->select('marcas.id as id','c.nombre as nombre')
        ->where('c.estado',1)
        ->get();//Solo se incluirán en el resultado las marcas que estén relacionadas con características cuyo estado sea activo (1).


        $categorias = Categoria::join('caracteristicas as c','categorias.caracteristica_id','=','c.id')
        ->select('categorias.id as id','c.nombre as nombre')
        ->where('estado',1)
        ->get();

        return view('producto.create', compact('marcas', 'categorias'));//compact('marcas'): Este método convierte la variable $marcas en un array asociativo, usar compact('marcas') es lo mismo que ['marcas' => $marcas]
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try{
            DB::beginTransaction();

            //---Tabla producto
            $producto = new Producto();
            //si en nuestro request existe un archivo llamado img_path, devuelve true
            if ($request->hasFile('img_path')) {
                $name = $producto->hanbleUploadImage($request->File('img_path'));
            } else {
                $name = null;
            }
            
            //El método fill() te permite asignar valores masivos a los atributos de un modelo(producto), Para que fill() funcione, los atributos deben estar incluidos en la propiedad $fillable del modelo.
            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'marca_id' => $request->marca_id
            ]);

            $producto->save();

            //---Tabla categoría producto
            $categorias = $request->get('categorias');
            $producto->categorias()->attach($categorias);//El método attach() es utilizado para insertar registros en la tabla pivote.

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('productos.index')->with('success','Producto registrado');
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
    public function edit(Producto $producto)
    {
        $marcas = Marca::join('caracteristicas as c','marcas.caracteristica_id','=','c.id')
        ->select('marcas.id as id','c.nombre as nombre')
        ->where('c.estado',1)
        ->get();//Solo se incluirán en el resultado las marcas que estén relacionadas con características cuyo estado sea activo (1).

        $categorias = Categoria::join('caracteristicas as c','categorias.caracteristica_id','=','c.id')
        ->select('categorias.id as id','c.nombre as nombre')
        ->where('estado',1)
        ->get();

        return view('producto.edit',compact('producto','marcas','categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try{
            DB::beginTransaction();

            //---Tabla producto
            //si en nuestro request existe un archivo llamado img_path, devuelve true
            if ($request->hasFile('img_path')) {
                $name = $producto->hanbleUploadImage($request->File('img_path'));

                //Eliminar si existiese una imagen antigua
                if (Storage::disk('public')->exists('productos/'.$producto->img_path)) {
                    Storage::disk('public')->delete('productos/'.$producto->img_path);
                }
                
            } else {
                $name = $producto->img_path;
            }
            
            //El método fill() te permite asignar valores masivos a los atributos de un modelo(producto), Para que fill() funcione, los atributos deben estar incluidos en la propiedad $fillable del modelo.
            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'marca_id' => $request->marca_id
            ]);

            $producto->save();

            //---Tabla categoría producto
            $categorias = $request->get('categorias');
            $producto->categorias()->sync($categorias);//El método sync() es utilizado para eliminar todos los registros existentes en la tabla pivote y luego inserta los nuevos registros que están en la variable $categorias 
            
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('productos.index')->with('success','Producto editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';

        //primera opción de código
        /*$producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id',$producto->id)->update(['estado' => 0]);
            $message = 'Producto eliminado';
        } else {
            Producto::where('id',$producto->id)->update(['estado' => 1]);
            $message = 'Producto restaurado';
        }*/
        
        // segunda opción de código, es más eficiente
        $estado = Producto::where('id', $id)->value('estado');
        if ($estado == 1) {
            Producto::where('id', $id)->update(['estado' => 0]);
            $message = 'Producto eliminado';
        } else {
            Producto::where('id', $id)->update(['estado' => 1]);
            $message = 'Producto restaurado';
        }

        return redirect()->route('productos.index')->with('success',$message);

    }

    public function inventoryPdf() 
    {
        //$productos = Producto::where('estado', 1)->get();

        // Subconsulta: obtener la fecha más reciente para cada producto
        $subquery = DB::table('compra_producto')->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))->groupBy('producto_id');

        // Join de productos con compra_producto usando la subconsulta
        $productos = DB::table('productos as p')
            ->leftJoin('marcas as m', 'p.marca_id', '=', 'm.id')
            ->leftJoin('caracteristicas as c', 'm.caracteristica_id', '=', 'c.id')
            ->leftJoinSub($subquery, 'cp_max', function ($join) {
                $join->on('p.id', '=', 'cp_max.producto_id');
            })
            ->leftJoin('compra_producto as cp', function ($join) {
                $join->on('cp.producto_id', '=', 'cp_max.producto_id')
                    ->on('cp.created_at', '=', 'cp_max.max_created_at');
            })
            ->where('p.estado', 1)
            ->select(
                'p.id',
                'p.nombre',
                'p.codigo',
                'p.stock',
                'p.estado',
                'c.nombre as marca_nombre',  // <- nombre desde caracteristicas
                'cp.precio_compra as precio_reciente'
            )
            ->get();
        
        //dd($productos);


        // Nota: aquí usamos Pdf::, no PDF::
        $pdf = Pdf::loadView('producto.inventory_pdf', compact('productos'))->setPaper('a4', 'landscape');

        return $pdf->stream('inventario_'.now()->format('Ymd').'.pdf');

        // return $pdf->download('inventario_'.now()->format('Ymd').'.pdf');

        //verificar a que si trae esto los precios mas nuevos de la tabla
        //$subquery = DB::table('compra_producto')->select('producto_id', DB::raw('MAX(created_at) as max_created_at'))->groupBy('producto_id');
        //return $subquery;

        //return view('producto.inventory_pdf');
    }
}
