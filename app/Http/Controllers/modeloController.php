<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModeloRequest;
use App\Http\Requests\UpdateModeloRequest;
use App\Models\Caracteristica;
use App\Models\Modelo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class modeloController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-modelo|crear-modelo|editar-modelo|eliminar-modelo',['only' => ['index']]);
        $this->middleware('permission:crear-modelo',['only' => ['create','store']]);
        $this->middleware('permission:editar-modelo', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-modelo', ['only'=> ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelos = Modelo::with('caracteristica')
            ->whereHas('caracteristica', function($query) {
                $query->where('estado', 1);
            })
            ->latest()
            ->get();

        return view('modelo.index', ['modelos' => $modelos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modelo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModeloRequest $request)
    {
        try{
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->modelo()->create([
                'caracteristica_id'=>$caracteristica->id
            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('modelos.index')->with('success','Modelo registrado');
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
    public function edit(Modelo $modelo)
    {
        return view('modelo.edit',['modelo' => $modelo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModeloRequest $request, Modelo $modelo)
    {
        Caracteristica::where('id', $modelo->caracteristica->id)->update($request->validated());

        return redirect()->route('modelos.index')->with('success','Modelo editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $modelo = Modelo::find($id);
        if ($modelo->caracteristica->estado == 1) {
            Caracteristica::where('id',$modelo->caracteristica->id)->update(['estado' => 0]);
            $message = 'Modelo eliminado';
        } else {
            Caracteristica::where('id',$modelo->caracteristica->id)->update(['estado' => 1]);
            $message = 'Modelo restaurado';
        }

        return redirect()->route('modelos.index')->with('success', $message);
    }

    public function eliminados()
    {
        $modelos = Modelo::with('caracteristica')
            ->whereHas('caracteristica', function($query) {
                $query->where('estado', 0);
            })
            ->latest()
            ->get();

        return view('modelo.modelos_eliminados', compact('modelos'));
    }
}
