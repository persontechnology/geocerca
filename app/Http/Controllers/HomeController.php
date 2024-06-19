<?php

namespace App\Http\Controllers;

use App\Models\Brazo;
use App\Models\Direccion;
use App\Models\Empresa;
use App\Models\Parqueadero;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Point;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }


    public function obtenerDirecciones($departamentoId)
    {
        // Suponiendo que tienes un modelo Dirección y la relación con el departamento está definida
        $direcciones = Direccion::where('departamento_id', $departamentoId)->get();
        // Retorna las direcciones como JSON
        return response()->json($direcciones);
    }


}
