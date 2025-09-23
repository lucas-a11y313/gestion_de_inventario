<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class logoutController extends Controller
{
    public function logout() {

        Session::flush();//Esto elimina todos los datos de la sesión actual, incluyendo: ID del usuario autenticado,Variables temporales guardadas con session()->put(...),Datos de flash como mensajes de éxito o error.Básicamente es como decir: “🧹 borra todo lo que Laravel haya guardado en la sesión del navegador”.

        Auth::logout();//Este método desloguea al usuario específicamente del sistema de autenticación de Laravel.

        return redirect()->route('login');
    }
}
