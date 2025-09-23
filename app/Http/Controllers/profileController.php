<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class profileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);//Acá me trae el id del usuario que está autenticado y con eso encontramos al usuarios con todos su datos,"usuario autenticado" es aquel cuya identidad ha sido verificada por un sistema

        return view('profile.index',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $profile)
    {
        // Obtener los datos validados convirtiendolos en un array 
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$profile->id,
            'password' => 'nullable'
        ]);//Convierte el objeto $request en un array con $request->all() o $request->validate() 
        
        //// Si no se ingresó nueva contraseña, la quitamos
        if(empty($data['password'])) {
            unset($data['password']);// Quita el campo 'password' si está vacío
        } else {

            //Encriptar el nuevo password y guardarlo dentro del campo password de $data
            $data['password'] = Hash::make($data['password']);//Hash proporciona un hash Bcrypt seguro para almacenar las contraseñas de los usuarios, básicamente encripta las contraseñas antes de guardarla en la base de datos
        }

        // Actualizar usuario con los datos procesados
        $profile->update($data);

        return redirect()->route('profile.index')->with('success','Cambios guardados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
