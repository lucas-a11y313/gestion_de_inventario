<?php

use App\Http\Controllers\AdquisicionController;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\InventarioBPController;
use App\Http\Controllers\InventarioInsumosController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\productoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\proveedorController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\userController;
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
//Route::get('/',[homeController::class, 'index'])->name('panel');//Esto le dice a Laravel que cuando alguien visite la ruta raiz '/', debe usar el método index del controlador homeController para manejar la solicitud.
Route::get('/panel',[homeController::class, 'index'])->name('panel')->middleware('auth');//Esto le dice a Laravel que cuando alguien visite '/panel', debe usar el método index del controlador homeController para manejar la solicitud. Este te trae la vista de panel

Route::get('/',[loginController::class, 'index'])->name('login');//Esto le dice a Laravel que cuando alguien visite la ruta raiz '/', debe usar el método index del controlador loginController para manejar la solicitud.
Route::post('/', [loginController::class, 'login'])->name('login.attempt');// Esta ruta se encargará de manejar toda la lógica para poder iniciar sesión
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');


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
    'proveedores' => proveedorController::class,
    'solicitudes' => SolicitudController::class,
    'users' => userController::class,
    'roles' => roleController::class,
    'profile' => profileController::class,
    'inventarioinsumos' => InventarioInsumosController::class,
    'inventariobp' => InventarioBPController::class
], ['parameters' => [
    'adquisiciones' => 'adquisicion'
]]);

Route::resource('adquisiciones', AdquisicionController::class, ['parameters' => ['adquisiciones' => 'adquisicion']]);
Route::resource('proyectos', ProyectoController::class, ['parameters' => ['proyectos' => 'proyecto']]);

// al final de routes/web.php
Route::get('solicitudes/{solicitude}/print', [SolicitudController::class, 'print'])->name('solicitudes.print')->middleware('permission:mostrar-solicitud');
Route::get('adquisiciones/{adquisicione}/print', [AdquisicionController::class, 'print'])->name('adquisiciones.print')->middleware('permission:mostrar-adquisicion');
Route::get('proyectos/{proyecto}/print-con-costo', [ProyectoController::class, 'printWithCost'])->name('proyectos.print.con-costo')->middleware('permission:mostrar-proyecto');
Route::get('proyectos/{proyecto}/print-sin-costo', [ProyectoController::class, 'printWithoutCost'])->name('proyectos.print.sin-costo')->middleware('permission:mostrar-proyecto');
Route::get('inventariobp/{inventariobp}/print', [InventarioBPController::class, 'print'])->name('inventariobp.print')->middleware('permission:mostrar-inventarioBP');
Route::get('inventariobp_pdf', [InventarioBPController::class, 'pdf'])->name('inventariobp.pdf')->middleware('permission:ver-inventarioBP');
Route::get('insumos/inventario/pdf',[InventarioInsumosController::class, 'pdf'])->name('insumos.inventario.pdf')->middleware('permission:ver-producto');
Route::get('productos/inventario/pdf',[productoController::class, 'inventoryPdf'])->name('productos.inventario.pdf')->middleware('permission:ver-producto');
Route::get('/gestion', [productoController::class, 'gestion'])->name('productos.gestion')->middleware('permission:ver-producto');






Route::get('/productos_eliminados', [productoController::class, 'eliminados'])->name('productos.eliminados');
Route::get('/marcas_eliminadas', [marcaController::class, 'eliminadas'])->name('marcas.eliminadas');
Route::get('/categorias_eliminadas', [categoriaController::class, 'eliminadas'])->name('categorias.eliminadas');

Route::get('/proveedores_eliminados', [proveedorController::class, 'eliminados'])->name('proveedores.eliminados');
Route::get('/adquisiciones_eliminadas', [AdquisicionController::class, 'eliminadas'])->name('adquisiciones.eliminadas');
Route::post('/adquisiciones/{id}/restaurar', [AdquisicionController::class, 'restaurar'])->name('adquisiciones.restaurar');
Route::get('/proyectos_eliminados', [ProyectoController::class, 'eliminados'])->name('proyectos.eliminados');
Route::post('/proyectos/{proyecto}/copiar', [ProyectoController::class, 'copiar'])->name('proyectos.copiar')->middleware('permission:crear-proyecto');
Route::get('/solicitudes_eliminadas', [SolicitudController::class, 'eliminadas'])->name('solicitudes.eliminadas');
Route::post('/solicitudes/{id}/restaurar', [SolicitudController::class, 'restore'])->name('solicitudes.restore');


Route::get('/insumosRetirados', [InventarioInsumosController::class, 'insumosRetirados'])->name('insumos.retirados');

Route::get('/insumosPrestados', [InventarioInsumosController::class, 'insumosPrestados'])->name('insumos.prestados');

Route::post('/insumos/devolver/{solicitud_id}/{producto_id}', [InventarioInsumosController::class, 'devolverInsumo'])->name('insumos.devolver');

Route::get('/insumos/origen', [InventarioInsumosController::class, 'origenInsumos'])->name('insumos.origen');

// AJAX endpoints for modals
Route::get('/inventariobp/{id}/data', [InventarioBPController::class, 'getData'])->name('inventariobp.getData');
Route::get('/inventarioinsumos/{id}/data', [InventarioInsumosController::class, 'getData'])->name('inventarioinsumos.getData');


// Esto atrapa todas las rutas que no matcheen con nada anterior
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
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
