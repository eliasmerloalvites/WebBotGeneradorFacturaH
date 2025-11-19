<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CocheraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EspacioParqueoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarcacionController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoVehiculoController;
use App\Http\Controllers\UsuarioCocheraController;
use App\Http\Controllers\VehiculoController;
use App\Models\UsuarioCochera;

Route::get('/', [HomeController::class,'login'])->name('login2');
Route::get('/ticketentrada/{id}', [HomeController::class,'ticketentrada'])->name('ticketentrada');
Route::get('/ticketcierre/{id}', [HomeController::class,'ticketentrada'])->name('ticketcierre');
Route::get('/ticketboleta/{id}', [HomeController::class,'ticketboleta'])->name('ticketboleta');
Route::get('/logueo', [UserController::class,'showlogin'])->name('login');
Route::post('/identificacion', [UserController::class,'verificarlogin'])->name('identificacion');
Route::get('/cancelarusuario',function(){
    return redirect()->route('usuario.index')->with('datos','Acción Cancelada...!');
  })->name('usuario.cancelar');
Route::post('/salir', [UserController::class,'salir'])->name('logout');




Route::middleware(['auth'])->group(function(){  
    Route::get('/home', [HomeController::class,'index'])->name('home');
    Route::get('personal/getimagen', [ProfileController::class,'getimagen'])->name('personal.getimagen');
    Route::resource('permiso', PermisoController::class);
    Route::resource('role', RoleController::class); 
    Route::resource('usuario', UserController::class);
    Route::resource('empresa', EmpresaController::class);
    Route::resource('operacion/cochera',CocheraController::class)->names([
      'index' => 'operacion.cochera.index',
      'create' => 'operacion.cochera.create',
      'store' => 'operacion.cochera.store',
      'edit' => 'operacion.cochera.edit',
      'update' => 'operacion.cochera.update',
      'destroy' => 'operacion.cochera.destroy',
      'show' => 'operacion.cochera.show'
    ]);
    Route::resource('operacion/usuario_cocheras',UsuarioCocheraController::class)->names([
      'index' => 'operacion.usuario_cocheras.index',
      'create' => 'operacion.usuario_cocheras.create',
      'store' => 'operacion.usuario_cocheras.store',
      'edit' => 'operacion.usuario_cocheras.edit',
      'update' => 'operacion.usuario_cocheras.update',
      'destroy' => 'operacion.usuario_cocheras.destroy',
      'show' => 'operacion.usuario_cocheras.show'
    ]);
    Route::get('/operacion/usuario_cocheras/list/cocheraxempresa/{id}', [UsuarioCocheraController::class, 'cocheraxempresa'])->name('operacion.usuario_cocheras.userall');
    Route::get('/operacion/usuario_cocheras/list/userall', [UsuarioCocheraController::class, 'userall'])->name('operacion.usuario_cocheras.userall');
    Route::get('/operacion/usuario_cocheras/list/userscochera', [UsuarioCocheraController::class, 'userscochera'])->name('operacion.usuario_cocheras.userscochera');

    Route::resource('operacion/tipo_vehiculo',TipoVehiculoController::class)->names([
      'index' => 'operacion.tipo_vehiculo.index',
      'create' => 'operacion.tipo_vehiculo.create',
      'store' => 'operacion.tipo_vehiculo.store',
      'edit' => 'operacion.tipo_vehiculo.edit',
      'update' => 'operacion.tipo_vehiculo.update',
      'destroy' => 'operacion.tipo_vehiculo.destroy',
      'show' => 'operacion.tipo_vehiculo.show'
    ]);
    Route::resource('operacion/espacios_parqueo',EspacioParqueoController::class)->names([
      'index' => 'operacion.espacios_parqueo.index',
      'create' => 'operacion.espacios_parqueo.create',
      'store' => 'operacion.espacios_parqueo.store',
      'edit' => 'operacion.espacios_parqueo.edit',
      'update' => 'operacion.espacios_parqueo.update',
      'destroy' => 'operacion.espacios_parqueo.destroy',
      'show' => 'operacion.espacios_parqueo.show'
    ]);
    Route::resource('gestion/tarifa',TarifaController::class)->names([
      'index' => 'gestion.tarifa.index',
      'create' => 'gestion.tarifa.create',
      'store' => 'gestion.tarifa.store',
      'edit' => 'gestion.tarifa.edit',
      'update' => 'gestion.tarifa.update',
      'destroy' => 'gestion.tarifa.destroy',
      'show' => 'gestion.tarifa.show'
    ]);
    Route::resource('gestion/marcacion',MarcacionController::class)->names([
      'index' => 'gestion.marcacion.index',
      'create' => 'gestion.marcacion.create',
      'store' => 'gestion.marcacion.store',
      'edit' => 'gestion.marcacion.edit',
      'update' => 'gestion.marcacion.update',
      'destroy' => 'gestion.marcacion.destroy',
      'show' => 'gestion.marcacion.show'
    ]);
    Route::post('gestion/marcacion/{id}/emitir-boleta', [MarcacionController::class, 'emitirBoleta'])
     ->name('gestion.marcacion.emitirBoleta');
    
    Route::post('/gestion/marcacion/storecerrar', [MarcacionController::class, 'storecerrar'])->name('gestion.marcacion.storecerrar');
    Route::get('/gestion/marcacion/list/espacios/{id}', [MarcacionController::class, 'espaciosAllfindCochera'])->name('gestion.marcacion.espaciosAllfindCochera');
    Route::get('/gestion/marcacion/show1/{marcacion}', [MarcacionController::class, 'show1'])->name('gestion.marcacion.show1');

    Route::resource('gestion/vehiculo', VehiculoController::class)->names([
      'index'   => 'gestion.vehiculo.index',
      'create'  => 'gestion.vehiculo.create',
      'store'   => 'gestion.vehiculo.store',
      'edit'    => 'gestion.vehiculo.edit',
      'update'  => 'gestion.vehiculo.update',
      'destroy' => 'gestion.vehiculo.destroy',
      'show'    => 'gestion.vehiculo.show'
  ]);

  Route::get('/vehiculo/buscar', [VehiculoController::class, 'buscar'])->name('gestion.vehiculo.buscar');
  Route::get('/vehiculos/filter-tipos', [VehiculoController::class, 'filterEspacio'])->name('gestion.vehiculo.filterEspacio');
  Route::get('/vehiculo/precio', [VehiculoController::class, 'obtenerPrecioPorPlaca'])->name('gestion.vehiculo.obtenerPrecioPorPlaca');

  Route::resource('gestion/cliente', ClienteController::class)->names([
    'index'   => 'gestion.cliente.index',
    'create'  => 'gestion.cliente.create',
    'store'   => 'gestion.cliente.store',
    'edit'    => 'gestion.cliente.edit',
    'update'  => 'gestion.cliente.update',
    'destroy' => 'gestion.cliente.destroy',
    'show'    => 'gestion.cliente.show'
  ]);
  
});