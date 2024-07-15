<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MisOrdenesMovilizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mis-ordenes-movilizacion.index');
    }

    
}
