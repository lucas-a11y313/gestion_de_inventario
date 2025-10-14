<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;
use App\Models\Proyecto;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class ProyectoController extends Controller
{
    function __construct()  {
        $this->middleware('permission:ver-proyecto|crear-proyecto|editar-proyecto|mostrar-proyecto|eliminar-proyecto',['only' => ['index']]);
        $this->middleware('permission:crear-proyecto',['only' => ['create','store']]);
        $this->middleware('permission:editar-proyecto',['only' => ['edit','update']]);
        $this->middleware('permission:mostrar-proyecto',['only' => ['show']]);
        $this->middleware('permission:eliminar-proyecto',['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyectos = Proyecto::where('estado',1)->with('productos')->latest()->get();

        // Calcular si hay existencias suficientes para cada proyecto
        foreach($proyectos as $proyecto) {
            $requisitosSatisfechos = true;
            foreach($proyecto->productos as $producto) {
                if($producto->stock < $producto->pivot->cantidad) {
                    $requisitosSatisfechos = false;
                    break;
                }
            }
            $proyecto->requisitos_satisfechos = $requisitosSatisfechos;
        }

        return view('proyecto.index',compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::where('estado',1)->get();
        return view('proyecto.create',compact('productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProyectoRequest $request)
    {
        try {
            DB::beginTransaction();

            // Manejar la imagen si existe
            if($request->hasFile('imagen')){
                $name = $this->handleUploadImage($request->file('imagen'));
            } else {
                $name = null;
            }

            // Crear proyecto
            $proyecto = Proyecto::create([
                'nombre' => $request->nombre,
                'fecha_ejecucion' => $request->fecha_ejecucion,
                'descripcion' => $request->descripcion,
                'imagen' => $name
            ]);

            // Asociar productos
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');

            if($arrayProducto_id) {
                $sizeArray = count($arrayProducto_id);
                $cont = 0;
                while($cont < $sizeArray) {
                    $proyecto->productos()->syncWithoutDetaching([
                        $arrayProducto_id[$cont] => [
                            'cantidad' => $arrayCantidad[$cont]
                        ]
                    ]);
                    $cont++;
                }
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            if(isset($name)){
                Storage::delete('public/proyectos/'.$name);
            }
            return redirect()->route('proyectos.create')->with('error', 'Error al crear el proyecto: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('proyectos.index')->with('success','Proyecto creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto)
    {
        $proyecto->load('productos.adquisiciones');

        // Calcular el valor y subtotal de cada producto
        $total = 0;
        foreach($proyecto->productos as $producto) {
            // Obtener el precio de adquisición más reciente
            $ultimaAdquisicion = $producto->adquisiciones->sortByDesc('pivot.created_at')->first();
            $precioAdquisicion = $ultimaAdquisicion?->pivot->precio_compra ?? 0;

            $cantidadRequerida = $producto->pivot->cantidad;

            $producto->precio_unitario = $precioAdquisicion;
            $producto->subtotal = $cantidadRequerida * $precioAdquisicion;
            $total += $producto->subtotal;
        }

        return view('proyecto.show',compact('proyecto', 'total'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto)
    {
        $productos = Producto::where('estado',1)->get();
        $proyecto->load('productos');
        return view('proyecto.edit',compact('proyecto','productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProyectoRequest $request, Proyecto $proyecto)
    {
        try {
            DB::beginTransaction();

            // Manejar la imagen
            if($request->hasFile('imagen')){
                $name = $this->handleUploadImage($request->file('imagen'));

                // Eliminar imagen anterior
                if($proyecto->imagen){
                    Storage::delete('public/proyectos/'.$proyecto->imagen);
                }
            } else {
                $name = $proyecto->imagen;
            }

            // Actualizar proyecto
            $proyecto->update([
                'nombre' => $request->nombre,
                'fecha_ejecucion' => $request->fecha_ejecucion,
                'descripcion' => $request->descripcion,
                'imagen' => $name
            ]);

            // Sincronizar productos
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');

            // Limpiar relaciones anteriores
            $proyecto->productos()->detach();

            if($arrayProducto_id) {
                $sizeArray = count($arrayProducto_id);
                $cont = 0;
                while($cont < $sizeArray) {
                    $proyecto->productos()->syncWithoutDetaching([
                        $arrayProducto_id[$cont] => [
                            'cantidad' => $arrayCantidad[$cont]
                        ]
                    ]);
                    $cont++;
                }
            }

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            if(isset($name) && $name != $proyecto->imagen){
                Storage::delete('public/proyectos/'.$name);
            }
            return redirect()->route('proyectos.edit', $proyecto)->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('proyectos.index')->with('success','Proyecto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Proyecto::where('id',$id)->update(['estado' => 0]);
        return redirect()->route('proyectos.index')->with('success','Proyecto eliminado');
    }

    public function eliminados()
    {
        $proyectos = Proyecto::where('estado', 0)->latest()->get();
        return view('proyecto.proyectos_eliminados', compact('proyectos'));
    }

    public function copiar(string $id)
    {
        try {
            DB::beginTransaction();

            $proyectoOriginal = Proyecto::with('productos')->findOrFail($id);

            // Crear copia del proyecto
            $proyectoCopia = Proyecto::create([
                'nombre' => $proyectoOriginal->nombre . ' (Copia)',
                'descripcion' => $proyectoOriginal->descripcion,
                'imagen' => $proyectoOriginal->imagen // Se mantiene la misma imagen
            ]);

            // Copiar productos con sus cantidades
            foreach($proyectoOriginal->productos as $producto) {
                $proyectoCopia->productos()->attach($producto->id, [
                    'cantidad' => $producto->pivot->cantidad
                ]);
            }

            DB::commit();
            return redirect()->route('proyectos.index')->with('success', 'Proyecto copiado exitosamente');
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('proyectos.index')->with('error', 'Error al copiar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for proyecto with costs
     */
    public function printWithCost(Proyecto $proyecto)
    {
        // Cargar las relaciones necesarias
        $proyecto->load('productos.adquisiciones');

        // Generar el PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('proyecto.proyecto_pdf_con_costo', ['proyecto' => $proyecto])
            ->setPaper('a4', 'portrait');

        // Retornar el PDF para visualización en el navegador
        return $pdf->stream('proyecto_' . $proyecto->id . '_con_costo_' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Generate PDF for proyecto without costs
     */
    public function printWithoutCost(Proyecto $proyecto)
    {
        // Cargar las relaciones necesarias
        $proyecto->load('productos');

        // Generar el PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('proyecto.proyecto_pdf_sin_costo', ['proyecto' => $proyecto])
            ->setPaper('a4', 'portrait');

        // Retornar el PDF para visualización en el navegador
        return $pdf->stream('proyecto_' . $proyecto->id . '_sin_costo_' . now()->format('Ymd') . '.pdf');
    }

    private function handleUploadImage($imagen) {
        $file = $imagen;
        $name = time()."_".$file->getClientOriginalName();
        Storage::putFileAs('public/proyectos/', $file, $name,'public');
        return $name;
    }
}
