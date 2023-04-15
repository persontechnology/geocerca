<?php

namespace App\Http\Controllers;

use App\Models\Brazo;
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
        return view('home');
    }

    public function geo()
    {
        // proceso
        // 1. consultar la web service para obtener todos los vehiculos
        // 2. consultar vehiculos del sistema por "ime"
        // 3. 
        
        
        $vehiculosWebService=[
            [
                "id"=>2,
                "placa"=>
                "XBA-0015",
                "imei"=>"50",
                "lat"=>-1.0431278651212645,
                "lng"=>-78.59085448337478
            ],
            [
                "id"=>3,
                "placa"=>
                "TGB-0012",
                "imei"=>"2",
                "lat"=>-1.0431278651212645,
                "lng"=>-78.59085448337478
            ]
        ];
    
        $vehiculosNUevos = array();
        foreach ($vehiculosWebService as $vw) {
            $vs=Vehiculo::where('imei',$vw['imei'])->first();
            if($vs){
                array_push($vehiculosNUevos,$vw);
            }
        }

        $parqueaderos=Parqueadero::get();

        foreach ($parqueaderos as $parqueadero) {
            $parqueadero->query()
            ->whereContains('area', new Point(0, 0, 4326))
            ->exists();
        }
        return $parqueaderos;
    }

}
