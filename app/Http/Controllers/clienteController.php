<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Persona;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class clienteController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-cliente|crear-cliente|editar-cliente|eliminar-cliente',['only' => ['index']]);
        $this->middleware('permission:crear-cliente',['only' => ['create','store']]);
        $this->middleware('permission:editar-cliente', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-cliente', ['only'=> ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('persona.documento')->latest()->get();

        return view('cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentos = Documento::all();//load() se utiliza para cargar la relacion que tiene una clase con otra, en este caso sería traer la relacion de cliente con persona. La razón por la que necesitas usar nuevamente $proveedore->load('persona.documento') en la función edit es porque Laravel no mantiene automáticamente las relaciones cargadas entre diferentes solicitudes HTTP, básicamente laravel no te trae las relaciones que cargaste amteriormente en la función index. Debes usar $proveedore->load('persona.documento') en el método edit porque cada solicitud HTTP es un proceso nuevo, y Laravel no mantiene los datos cargados previamente.

        return view('cliente.create', compact('documentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try{
            DB::beginTransaction();
            $persona = Persona::create($request->validated());
            $persona->cliente()->create([
                'persona_id' => $request->id
            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('clientes.index')->with('success','Cliente registrado');
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
    public function edit(Cliente $cliente)
    {   
        $cliente->load('persona.documento');//load() se utiliza para cargar la relacion que tiene una clase con otra, en este caso sería traer la relacion de cliente con persona. La razón por la que necesitas usar nuevamente $proveedore->load('persona.documento') en la función edit es porque Laravel no mantiene automáticamente las relaciones cargadas entre diferentes solicitudes HTTP, básicamente laravel no te trae las relaciones que cargaste amteriormente en la función index. Debes usar $proveedore->load('persona.documento') en el método edit porque cada solicitud HTTP es un proceso nuevo, y Laravel no mantiene los datos cargados previamente.
        $documentos = Documento::all();

        //dd($cliente);
        
        return view('cliente.edit',compact('cliente','documentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        try{
            DB::beginTransaction();

            Persona::where('id',$cliente->persona_id)->update($request->validated());

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('clientes.index')->with('success','Cliente editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';

        $personaId = Cliente::where('id',$id)->value('persona_id');
        
        $persona = Persona::find($personaId);
        
        if ($persona->estado == 1) {
            Persona::where('id',$persona->id)->update(['estado' => 0]);
            $message = 'Cliente eliminado';
        } else {
            Persona::where('id',$persona->id)->update(['estado' => 1]);
            $message = 'Cliente restaurado';
        }

        return redirect()->route('clientes.index')->with('success',$message);

    }

    public function eliminados()
    {
        $clientes = Cliente::with('persona.documento')
            ->whereHas('persona', function($query) {
                $query->where('estado', 0);
            })
            ->latest()
            ->get();

        return view('cliente.clientes_eliminados', compact('clientes'));
    }
}
