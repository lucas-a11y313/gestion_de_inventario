<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index() {
        $cantidadInsumos = Producto::where('tipo', 'Insumo')->count();
        return view('panel.index', compact('cantidadInsumos'));
    }
}
