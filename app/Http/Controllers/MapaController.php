<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Parqueadero;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;

class MapaController extends Controller
{


    public function __construct()
    {
        $this->middleware(['permission:Mapa']);
    }

    public function index()
    {
        $empresa=Empresa::first();
        return view('mapa.index',['empresa'=>$empresa]);
    }

}
