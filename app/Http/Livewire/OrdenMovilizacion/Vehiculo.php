<?php

namespace App\Http\Livewire\OrdenMovilizacion;

use App\Models\Parqueadero;
use App\Models\Vehiculo as ModelsVehiculo;
use Livewire\Component;

class Vehiculo extends Component
{
    

    public $foo;
    public $search = '';
    public $page = 1;
 
    protected $queryString = [
        'foo',
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $vehiculos=ModelsVehiculo::where('numero_movil','like','%'.$this->search.'%')
            ->orWhere('placa','like','%'.$this->search.'%')->get();
        
        
        $data = array(
            'vehiculos'=>$vehiculos,
        );
        return view('livewire.orden-movilizacion.vehiculo',$data);
    }
}
