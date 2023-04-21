<?php

namespace App\Http\Controllers;

use App\Models\Brazo;
use App\Models\Empresa;
use App\Models\Parqueadero;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
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
        $empresa=Empresa::first();
        return view('home',['empresa'=>$empresa]);
    }

    public function geo()
    {

        $parqueaderos=Parqueadero::get();

        foreach ($parqueaderos as $parqueadero) {
            $parqueadero->query()
            ->whereContains('area', new Point(0, 0, 4326))
            ->exists();
        }
        return $parqueaderos;
    }

}
