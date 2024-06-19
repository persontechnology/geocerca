<?php

use App\DataTables\DepartamentoDataTable;
use App\Http\Controllers\ConexionesApisController;
use App\Http\Controllers\ControlOrdenMovilizacionController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DespachoCombustibleController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IngresoCombustibleController;
use App\Http\Controllers\IngresoKilometrajeController;
use App\Http\Controllers\KilometrajeController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\MisOrdenesMovilizacion;
use App\Http\Controllers\MisOrdenesMovilizacionController;
use App\Http\Controllers\OrdenMovilizacionController;
use App\Http\Controllers\ParqueaderoController;
use App\Http\Controllers\Reportes\DashboardVehiculoController;
use App\Http\Controllers\RolesPermisosController;
use App\Http\Controllers\Usuarios\GuardiaController;
use App\Http\Controllers\Usuarios\PerfilController;
use App\Http\Controllers\Usuarios\UsuarioController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');

    // Artisan::call('cache:clear');
    // Artisan::call('config:clear');
    // Artisan::call('config:cache');
    // Artisan::call('storage:link');
    // Artisan::call('key:generate');
    // Artisan::call('migrate:fresh --seed');


})->name('welcome');





Auth::routes(['verify' => true, 'register' => false]);


    
Route::get('/getConections', [ConexionesApisController::class,'getConections']);

Route::middleware(['verified', 'auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/obtener-direcciones-x-departamento/{departamentoId}', [HomeController::class, 'obtenerDirecciones']);
    

    // pruebas
    Route::get('/geo', [HomeController::class, 'geo'])->name('geo');
    // mapa
    Route::get('/mapa', [MapaController::class, 'index'])->name('mapa.index');

    // Deivid,Perfil de usuario
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil-actualizar', [PerfilController::class, 'actualizar'])->name('actualizarPerfil');
    Route::post('/perfil-actualizar-contrasena', [PerfilController::class, 'actualizarContrasena'])->name('actualizarContrasena');
    Route::get('/configuracion', [PerfilController::class, 'configuracion'])->name('configuracion');
    Route::post('/perfil-actualizar-configuracion', [PerfilController::class, 'actualizarConfiguracion'])->name('actualizarConfiguracion');

    // roles y permisos
    Route::get('/roles', [RolesPermisosController::class, 'index'])->name('roles');
    Route::post('/roles-guardar', [RolesPermisosController::class, 'guardar'])->name('guardarRol');
    Route::post('/roles-actualizar', [RolesPermisosController::class, 'actualizar'])->name('actualizarRol');
    Route::post('/roles-eliminar', [RolesPermisosController::class, 'eliminar'])->name('eliminarRol');


    // usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/usuarios-nuevo', [UsuarioController::class, 'nuevo'])->name('usuariosNuevo');
    Route::post('/usuarios-guardar', [UsuarioController::class, 'guardar'])->name('guardarUusario');
    Route::get('/usuarios-editar/{id}', [UsuarioController::class, 'editar'])->name('usuariosEditar');
    Route::post('/usuarios-actualizar', [UsuarioController::class, 'actualizar'])->name('actualizarUsuario');
    Route::post('/usuarios-eliminar', [UsuarioController::class, 'eliminar'])->name('usuariosEliminar');
    Route::get('/usuarios-rol/{rol}', [UsuarioController::class, 'usuariosPoRol'])->name('usuariosPoRol');
    Route::get('/usuarios-ingresar/{id}', [UsuarioController::class, 'ingresar'])->name('usuariosIngresar');


    // empresa
    Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa');
    Route::post('/actualizarEmpresa', [EmpresaController::class, 'actualizar'])->name('actualizarEmpresa');
    

    // vehiculos
    Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos');
    Route::get('/vehiculos-guardar-tipo', [VehiculoController::class, 'guardarTipo'])->name('vehiculosGuardarTipo');
    Route::post('/vehiculos-eliminar-tipo', [VehiculoController::class, 'eliminarTipo'])->name('vehiculosEliminarTipo');
    Route::get('/vehiculos-nuevo', [VehiculoController::class, 'nuevo'])->name('vehiculosNuevo');
    Route::post('/vehiculos-guardar', [VehiculoController::class, 'guardar'])->name('guardarVehiculo');
    Route::get('/vehiculos-editar/{id}', [VehiculoController::class, 'editar'])->name('vehiculosEditar');
    Route::post('/vehiculos-actualizar', [VehiculoController::class, 'actualizar'])->name('actualizarVehiculo');
    Route::post('/vehiculos-eliminar', [VehiculoController::class, 'eliminar'])->name('vehiculosEliminar');
    Route::get('/vehiculos-ubicacion-mapa/{id}', [VehiculoController::class, 'ubicacionMapa'])->name('vehiculosUbicacionMapa');
    Route::get('/vehiculos-reporte-pdf', [VehiculoController::class, 'reportePdf'])->name('vehiculosReportePdf');
    


    
    // vehiculos orden de movilización
    Route::get('/vehiculos-orden-movilizaciones/{id}', [VehiculoController::class, 'ordenMovilizaciones'])->name('vehiculosOrdenMovilizacion');  
    

    // kilometrajes
    Route::get('/kilometrajes/{id}', [KilometrajeController::class, 'index'])->name('kilometrajes');


    // orden de movilizacion
    Route::get('/orden-movilizacion', [OrdenMovilizacionController::class, 'index'])->name('odernMovilizacion');
    Route::post('/orden-movilizacion-guardar', [OrdenMovilizacionController::class, 'guardar'])->name('odernMovilizacionGuardar');
    Route::get('/orden-movilizacion-editar/{id}', [OrdenMovilizacionController::class, 'editar'])->name('odernMovilizacionEditar');
    Route::post('/orden-movilizacion-actualizar', [OrdenMovilizacionController::class, 'actualizar'])->name('odernMovilizacionActualizar');
    Route::post('/orden-movilizacion-eliminar', [OrdenMovilizacionController::class, 'eliminar'])->name('odernMovilizacionEliminar');
    Route::post('/orden-movilizacion-obtener', [OrdenMovilizacionController::class, 'obtener'])->name('odernMovilizacionObtener');
    Route::get('/orden-movilizacion-listado', [OrdenMovilizacionController::class, 'listado'])->name('odernMovilizacionListado');
    Route::get('/orden-movilizacion-pdf/{id}', [OrdenMovilizacionController::class, 'pdf'])->name('odernMovilizacionPdf');
    Route::get('/orden-movilizacion-lecturas/{id}', [OrdenMovilizacionController::class, 'lecturas'])->name('odernMovilizacionLecturas');
    Route::post('/orden-movilizacion-lectura-actualizar', [OrdenMovilizacionController::class, 'lecturaActualizar'])->name('odernMovilizacionLecturaActualizar');
    Route::get('/orden-movilizacion-reporte-pdf', [OrdenMovilizacionController::class, 'reportePdf'])->name('odernMovilizacionReportePdf');
    Route::get('/orden-movilizacion-multiple', [OrdenMovilizacionController::class, 'multiple'])->name('odernMovilizacionMultiple');
    Route::post('/orden-movilizacion-multiple-guardar', [OrdenMovilizacionController::class, 'multipleGuardar'])->name('odernMovilizacionMultipleGuardar');
    
    
    
    

    

    // lecturas
    Route::get('/lecturas', [LecturaController::class, 'index'])->name('lecturas');
    Route::post('/lecturas-eliminar', [LecturaController::class, 'eliminar'])->name('lecturaEliminar');
    Route::get('/lecturas-editar/{id}', [LecturaController::class, 'editar'])->name('lecturaEditar');
    Route::post('/lecturas-actualizar', [LecturaController::class, 'actualizar'])->name('lecturaActualizar');
    
    

    // control orden de mobilizacion
    Route::get('/control-odern-movilizacion', [ControlOrdenMovilizacionController::class, 'index'])->name('controlOdernMovilizacion');
    Route::get('/control-odern-movilizacion-aprobar-denegar/{id}', [ControlOrdenMovilizacionController::class, 'AprobarReprobar'])->name('controlOdernMovilizacionAprobarReprobar');
    Route::post('/control-odern-movilizacion-aprobar-dnegar-guardar', [ControlOrdenMovilizacionController::class, 'AprobarReprobarGuardar'])->name('controlOdernMovilizacionAprobarReprobarGuardar');
    Route::get('/control-odern-movilizacion-pdf/{id}', [ControlOrdenMovilizacionController::class, 'AprobarReprobarPdf'])->name('controlOdernMovilizacionPdf');
    Route::post('/control-odern-movilizacion-aprobar-lista', [ControlOrdenMovilizacionController::class, 'AprobarListaGuardar'])->name('controlOdernMovilizacionAprobarLista');
    // parqueaderos
    Route::get('/parqueaderos', [ParqueaderoController::class, 'index'])->name('parqueaderos');
    Route::get('/parqueaderos-nuevo', [ParqueaderoController::class, 'nuevo'])->name('parqueaderosNuevo');
    Route::post('/parqueaderos-guardar', [ParqueaderoController::class, 'guardar'])->name('parqueaderosGuardar');
    Route::get('/parqueaderos-editar/{id}', [ParqueaderoController::class, 'editar'])->name('parqueaderosEditar');
    Route::post('/parqueaderos-actualizar', [ParqueaderoController::class, 'actualizar'])->name('parqueaderosActualizar');
    Route::post('/parqueaderos-eliminar', [ParqueaderoController::class, 'eliminar'])->name('parqueaderosEliminar');

    // espacios    
    

    // despacho de combustible
    Route::resource('despacho-combustible', DespachoCombustibleController::class);
    Route::get('/despacho-combustible-pdf/{id}', [DespachoCombustibleController::class, 'pdf'])->name('despacho-combustible.pdf');
    // estaciones de combustible
    Route::resource('estacion', EstacionController::class);


    // ingreso de kilometraje
    Route::get('ingresar-kilometraje', [IngresoKilometrajeController::class,'ingresarKilometraje'])->name('ingresoKilometraje.ingresar');
    Route::post('guardar-kilometraje', [IngresoKilometrajeController::class,'guardarKilometraje'])->name('ingresoKilometraje.guardar');
    
    // Ingreso de comustible
    
    Route::get('mis-ingresos-de-combustible', [IngresoCombustibleController::class,'index'])->name('ingresoCombustible.index');
    Route::get('ingresar-combustible/{id}', [IngresoCombustibleController::class,'ingresar'])->name('ingresoCombustible.ingresar');
    Route::get('ingresar-combustible-pdf/{id}', [IngresoCombustibleController::class,'pdf'])->name('ingresoCombustible.pdf');
    Route::post('guardar-combustible', [IngresoCombustibleController::class,'guardar'])->name('ingresoCombustible.guardar');


    // mis ordenes de movilizacion
    Route::resource('mis-ordenes-movilizacion', MisOrdenesMovilizacionController::class);

    Route::resource('direcciones-departamentos', DepartamentoController::class);
    Route::post('direcciones-departamentos.guardar', [DepartamentoController::class,'guardar'])->name('direcciones-departamentos.guardar');
    Route::post('direcciones-eliminar', [DepartamentoController::class,'eliminarDireccion'])->name('direcciones.eliminar');
    Route::post('departamentos-eliminar', [DepartamentoController::class,'eliminarDepartamento'])->name('departamentos.eliminar');
    

    


     
});
