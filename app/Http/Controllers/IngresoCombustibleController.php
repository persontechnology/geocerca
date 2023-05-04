<?php

namespace App\Http\Controllers;

use App\DataTables\IngresoCombustibleVehiculoDataTable;
use Illuminate\Http\Request;

class IngresoCombustibleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ingreso de Combustible']);
    }

    public function ingresar(IngresoCombustibleVehiculoDataTable $dataTable)
    {
        
        return $dataTable->render('ingresoCombustible.ingresar');
    }
    public function guardar(Request $request)
    {
        
    }
}
