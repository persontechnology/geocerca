<?php

namespace App\Http\Controllers\Usuarios;

use App\DataTables\Usuarios\Guardia\IngresarKilometrajeDataTable;
use App\Http\Controllers\Api\GeocercaController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\Guardia\RqGuardarKilometraje;
use App\Models\Empresa;
use App\Models\Kilometraje;
use App\Models\Vehiculo;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GuardiaController extends Controller
{

   
}
