<?php

use App\Http\Controllers\Api\BrazoController;
use App\Http\Controllers\Api\DespachoCombustibleController;
use App\Http\Controllers\Api\GeocercaController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LecturaController;
use App\Http\Controllers\Api\LecturaEspecialController;
use App\Http\Controllers\Api\LecturaInvitadoController;
use App\Http\Controllers\Api\LecturaNormalController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\NotificacionLecturaController;
use App\Http\Controllers\ConexionesApisController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Deivid; Acceso a la API
Route::post('/login', [LoginController::class,'login']);
Route::post('/reset-password', [LoginController::class,'resetPassword']);


// Deivid, geocerca
Route::get('/coordenadas-autos', [GeocercaController::class,'coordenadasAutos'])->name('coordenadasAutos');
Route::get('/coordenadas-autos-mapa', [GeocercaController::class,'coordenadasAutosMapa'])->name('coordenadasAutosMapa');
Route::get('/coordenadas-parqueaderos', [GeocercaController::class,'coordenadasParqueaderos'])->name('coordenadasParqueaderos');


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/actualizar-contrasena', [HomeController::class,'actualizarContrasena']);
    

    // despacho de combustible
    Route::post('/dc-consulta', [DespachoCombustibleController::class,'consulta']);
    Route::post('/dc-consulta-estaciones', [DespachoCombustibleController::class,'consultaEstaciones']);
    Route::post('/dc-enviarFoto', [DespachoCombustibleController::class,'guardarFoto']);
    

});
