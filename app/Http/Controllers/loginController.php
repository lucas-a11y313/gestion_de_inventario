<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function index() {

        //Funcionamiento del codigo de abajo: Si el usuario ya inició sesión anteriormente, no tiene sentido mostrarle el formulario de login de nuevo. Por eso lo redirige directamente al panel de usuario. Si no ha iniciado sesión, le muestra el formulario de login normalmente.


        //El método "Auth::check()" verifica si ya hay un usuario autenticado (logueado) en la sesión.
        if(Auth::check()) {
            return redirect()->route('panel');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request) {
        
        //VALIDAR CREDENCIALES
        //only() hace que agarre solo los valores que solicites, en este caso serían email y password
        if(!Auth::validate($request->only('email','password'))) {//aca se verifica si no existe un usuario con estas credenciales, si existe entonces se niega y sale del if; si no existe se niega el false y pasa a true, asi entrando en el if 
            return redirect()->to('login')->withErrors('Credenciales incorrectas');
        }
        
        //CREAR UNA SESIÓN
        $user = Auth::getProvider()->retrieveByCredentials($request->only('email','password'));//Esta línea busca al usuario en la base de datos utilizando las credenciales (email y password),pero NO verifica la contraseña ni inicia sesión todavía.
        Auth::login($user);//Inicia sesión con ese usuario. Es decir, Laravel: Lo autentica como el usuario activo, Crea la sesión y Guarda su ID en la sesión (para recordarlo en las próximas peticiones).

        return redirect()->route('panel')->with('success','Bienvenido '.$user->name);
    }
}
