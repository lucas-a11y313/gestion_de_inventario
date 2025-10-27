<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Caracteristica;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
USE Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
class categoriaController extends Controller
{
    function __construct() 
    {
        $this->middleware('permission:ver-categoria|crear-categoria|editar-categoria|eliminar-categoria',['only' => ['index']]);//only hace que nos permita usar la funciòn index, solo aquellos usuarios que tengan los 4 permisos ver/crear/editar/eliminar van a poder ver la funcion index
        $this->middleware('permission:crear-categoria',['only' => ['create', 'store']]);
        $this->middleware('permission:editar-categoria',['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-categoria',['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * public function index()es utilizado para manejar la solicitud HTTP GET al recurso principal de "categorías". Su objetivo es mostrar una lista o vista relacionada con este recurso.
     */
    public function index()
    {
        $categorias = Categoria::with('caracteristica')
            ->whereHas('caracteristica', function($query) {
                $query->where('estado', 1);
            })
            ->latest()
            ->get();//Traer o recuperar el modelo categoria con el modelo caracteristica a traves de la relacion que nos da la funcion 'caracteristica' que está en el modelo Categoria, la relacion es de uno a uno. latest() Nos permite ordenar los registros de la tabla segun su fecha de creación, se estará aplicando un "order by"

        //dd($categorias);

        return view('categoria.index',['categorias' => $categorias]);//['categorias' => $categorias]:se va a enviar una variable categorias y que esa variable sea igual a $categorias, eso nos permite mostrar los valores que contiene $categorias en la vista.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoria.create');
    }

    /**
     * Store a newly created resource in storage. 
     * Almacenar un recurso recién creado en el almacenamiento.
     */
    public function store(StoreCategoriaRequest $request)
    {
        /*dd($request);*
        
        /**dd($variable) es una función muy útil para depuración (debugging).Muestra el contenido de la variable o expresión que pases como argumento.Puede ser una variable simple, un arreglo, un objeto, etc. */
        try{
            DB::beginTransaction();//Inicia una nueva transacción.

            // Crear la característica con los datos validados del request
            $caracteristica = Caracteristica::create($request->validated());//El método $request->validated() devuelve solo los datos que pasaron la validación, eliminando cualquier dato no permitido o no definido en las reglas.

            // Crear la categoría asociada a la característica
            $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            /*  $caracteristica->categoria() hace referencia a la relación categoria definida en el modelo.
                ->create() permite insertar un nuevo registro en la tabla categorias, asignando automáticamente la clave foránea (caracteristica_id) basada en el id de la característica.
                $caracteristica->categoria()->create([...]) utiliza la relación hasOne para insertar automáticamente una categoría con el caracteristica_id correcto.
            */
            DB::commit();//Finaliza la transacción y confirma todos los cambios realizados dentro de ella.
            
        }catch(Exception $e){
            DB::rollBack();// Revertir todos los cambios si ocurre un error
        }

        return redirect()->route('categorias.index')->with('success','Categoría registrada');
        /*success es la clave que se enviará a categorias.index que se verificará en el @if(session()) para que se pueda dar el mensaje de que la categoria ha sido registrada correctamente
        Acá se aplica el codigo de estado de "respuesta HTTP"(HTTP response) que indica si se ha completado una solicitud HTTP específica, en este caso el mensaje sería: 'Categoría registrada'
        */
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
    public function edit(Categoria $categoria)
    {   
        return view('categoria.edit', ['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        Caracteristica::where('id',$categoria->caracteristica->id)->update($request->validated());//si el id de caracteristica es igual al id de $categoria->caracteristica->id entonces podes actualizar dicho registro con los valores de $request

        return redirect()->route('categorias.index')->with('success','Categoría editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->caracteristica->delete(); // Al eliminar la característica, la categoría y la relación en categoria_producto se eliminan en cascada.

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada permanentemente.');
    }

    public function eliminadas()
    {
        $categorias = Categoria::with('caracteristica')
            ->whereHas('caracteristica', function($query) {
                $query->where('estado', 0);
            })
            ->latest()
            ->get();

        return view('categoria.categorias_eliminadas', compact('categorias'));
    }
}
