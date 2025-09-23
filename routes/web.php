<?php

use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraCrontroller;
use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\presentacionController;
use App\Http\Controllers\productoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\proveedorController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\userController;
use App\Http\Controllers\ventaController;
use Illuminate\Support\Facades\Route;




//Route::view('/panel', 'panel.index')->name('panel');    este es una version corta para mostrar una vista, el de abajo es una sintax más larga

/*
Route::get('/panel', function(){
    return view('panel.index');
})->name('panel');
*/

/*
                    /\
                  / || \
                    ||
                    ||
*/
//Esta version tambien trae a panel.index solo que a través del controlador homeController
Route::get('/',[homeController::class, 'index'])->name('panel');//Esto le dice a Laravel que cuando alguien visite la ruta raiz '/', debe usar el método index del controlador homeController para manejar la solicitud.

/* Podes optar por 1)llamar una ruta para cada recurso o 2)llamar una ruta para todos los recursos de tipo controlador
1)
Route::resource('categorias',categoriaController::class);
Route::resource('marcas', marcaController::class);
Route::resource('preoductos',productoController::class);
*/
//2)
Route::resources([
    'categorias' => categoriaController::class,
    'marcas' => marcaController::class,
    'productos' => productoController::class,
    'clientes' => clienteController::class,
    'proveedores' => proveedorController::class,
    'compras' => compraCrontroller::class,
    'ventas' => ventaController::class,
    'users' => userController::class,
    'roles' => roleController::class,
    'profile' => profileController::class
]);

// al final de routes/web.php
Route::get('ventas/{venta}/print', [App\Http\Controllers\ventaController::class, 'print'])->name('ventas.print')->middleware('permission:mostrar-venta');


Route::get('/ticket',function (){
    return view('ticket.index');
});

Route::get('/login', [loginController::class, 'index'])->name('login');//Esto le dice a Laravel que cuando alguien visite '/login', debe usar el método index del controlador homeController para manejar la solicitud. Este te trae la vista de login
Route::post('/login', [loginController::class, 'login']);// Esta ruta se encargará de manejar toda la lógica para poder iniciar sesión
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

// Esto atrapa todas las rutas que no matcheen con nada anterior
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('productos/inventario/pdf',[productoController::class, 'inventoryPdf'])->name('productos.inventario.pdf');


Route::get('/productos_eliminados',function (){
    return view('producto.productos_eliminados');
});




Route::get('/inventarioBP', function () {
    return view('InventarioBP.index');
})->name('inventarioBP');

Route::get('/inventarioBPC', function () {
    return view('InventarioBP.create');
});

Route::get('/inventarioBPE', function () {
    return view('InventarioBP.edit');
});



Route::get('/inventarioIN', function () {
    return view('InventarioInsumos.index');
})->name('inventarioIN');

Route::get('/inventarioINC', function () {
    return view('InventarioInsumos.create');
});

Route::get('/inventarioINE', function () {
    return view('InventarioInsumos.edit');
});

/*Route::get('/401', function () {
    return view('errors.401');
});

Route::get('/404', function () {
    return view('pages.404');
});
Route::get('/500', function () {
    return view('pages.500');
});*/

