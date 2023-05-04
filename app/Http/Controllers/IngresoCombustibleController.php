<?php

namespace App\Http\Controllers;

use App\DataTables\IngresoCombustibleVehiculoDataTable;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;


class IngresoCombustibleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ingreso de Combustible']);
    }

    public function ingresar(IngresoCombustibleVehiculoDataTable $dataTable,Request $request)
    {
        $ip = $request->ip();
        if ($position = Location::get($ip)) {
            // Successfully retrieved position.
           return $position;
        } else {
            return 'no';
        }

        // return  $ip = $request->ip();
        // return $dataTable->render('ingresoCombustible.ingresar');
    }
    public function guardar(Request $request)
    {
        
    }
}
