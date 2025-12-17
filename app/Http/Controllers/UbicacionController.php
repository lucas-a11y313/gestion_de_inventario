<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use App\Http\Requests\StoreUbicacionRequest;
use App\Http\Requests\UpdateUbicacionRequest;
use Illuminate\Routing\Controller;

class UbicacionController extends Controller
{
    function __construct()
    {
        //A AÑADIR LOS PERMISOS DE UBICACION 
        /*
        $this->middleware('permission:ver-ubicacion|crear-ubicacion|editar-ubicacion|eliminar-ubicacion', ['only' => ['index']]);
        $this->middleware('permission:crear-ubicacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-ubicacion', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-ubicacion', ['only' => ['destroy']]);
        */
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ubicaciones = Ubicacion::where('estado', 1)->get();
        return view('ubicacion.index', compact('ubicaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ubicacion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUbicacionRequest $request)
    {
        Ubicacion::create($request->validated());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación registrada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ubicacion $ubicacion)
    {
        // Not implemented
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ubicacion $ubicacion)
    {
        return view('ubicacion.edit', compact('ubicacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUbicacionRequest $request, Ubicacion $ubicacion)
    {
        $ubicacion->update($request->validated());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->estado = 0;
        $ubicacion->save();
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación eliminada');
    }
}
