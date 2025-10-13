<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Documento;
use App\Models\Persona;
use App\Models\Proveedore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class proveedorController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-proveedor|crear-proveedor|editar-proveedor|eliminar-proveedor',['only' => ['index']]);
        $this->middleware('permission:crear-proveedor',['only' => ['create','store']]);
        $this->middleware('permission:editar-proveedor',['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-proveedor',['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedore::with('persona.documento')->latest()->get();
        return view('proveedor.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();
        
        return view('proveedor.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try{
            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->proveedore()->create([
                'persona_id' => $persona->id
            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('proveedores.index')->with('success','Proveedor registrado');
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
    public function edit(Proveedore $proveedore)
    {
        
        $proveedore->load('persona.documento');//load() se utiliza para cargar la relacion que tiene una clase con otra, en este caso sería traer la relacion de cliente con persona. La razón por la que necesitas usar nuevamente $proveedore->load('persona.documento') en la función edit es porque Laravel no mantiene automáticamente las relaciones cargadas entre diferentes solicitudes HTTP, básicamente laravel no te trae las relaciones que cargaste amteriormente en la función index. Debes usar $proveedore->load('persona.documento') en el método edit porque cada solicitud HTTP es un proceso nuevo, y Laravel no mantiene los datos cargados previamente.

        $documentos = Documento::all();

        return view('proveedor.edit',compact('proveedore','documentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedore $proveedore)
    {
        try{
            DB::beginTransaction();

            Persona::where('id',$proveedore->persona_id)->update($request->validated());
            
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('proveedores.index')->with('success','Proveedor editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        
        $personaId = Proveedore::where('id',$id)->value('persona_id');
        
        $persona = Persona::find($personaId);
        
        if ($persona->estado == 1) {
            Persona::where('id',$persona->id)->update(['estado' => 0]);
            $message = 'Proveedor eliminado';
        } else {
            Persona::where('id',$persona->id)->update(['estado' => 1]);
            $message = 'Proveedor restaurado';
        }
        
        return redirect()->route('proveedores.index')->with('success',$message);
    }

    public function eliminados()
    {
        $proveedores = Proveedore::with('persona.documento')
            ->whereHas('persona', function($query) {
                $query->where('estado', 0);
            })
            ->latest()
            ->get();

        return view('proveedor.proveedores_eliminados', compact('proveedores'));
    }
}
