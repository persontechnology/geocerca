<?php

namespace App\Http\Livewire\Vehiculos;

use App\Models\Parqueadero;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Livewire\Component;
use PDF;
use Livewire\WithPagination;

class ReposrtePdf extends Component
{
    

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    // para filtros
    public $IdTipoVehiculo;
    public $NumeroOrden;
    public $IdParqueadero;
    public $mostrar='10';
    // querys
    protected $queryString = [
        'NumeroOrden' => ['except' => '','as'=>'orden'],
        'IdTipoVehiculo'=>['except' => '','as'=>'tipovehiculo'],
        'IdParqueadero'=>['except' => '','as'=>'parqueadero'],
        'mostrar'=>['except'=>'10']
    ];

    public function render()
    {
        $data = array(
            'ordenMovilizaciones'=>$this->listadoOrdenes(),
            'tipoVehiculos'=>TipoVehiculo::get(),
            'parqueaderos'=>Parqueadero::get(),
        );
        return view('livewire.vehiculos.reporte-pdf',$data);
    }

    public function listadoOrdenes(){
        

        $ordenMovilizaciones=Vehiculo::where(function ($query)  {
            
           
            if($this->NumeroOrden){
                $query->where('numero_movil', 'like', '%'.$this->NumeroOrden.'%');
            }

            if($this->IdTipoVehiculo){
                $query->where('tipo_vehiculo_id', 'like', '%'.$this->IdTipoVehiculo.'%');
            }

            if($this->IdParqueadero){
                $query->where('parqueadero_id', 'like', '%'.$this->IdParqueadero.'%');
            }
            
        })
        ->latest()->paginate($this->mostrar);
        
        return $ordenMovilizaciones;
    }

    public function updating()
    {
        $this->resetPage();
    }
    

    public function updatedIdTipoVehiculo($value)
    {
        
        $this->IdTipoVehiculo=$value;
    }

    

    public function updatedIdParqueadero($value)
    {
        
        $this->IdParqueadero=$value;

    }
    public function updatedMostrar($value)
    {
        $this->mostrar=$value;
    }

    public function pdf()
    {
        $ordenes=$this->listadoOrdenes();
        $headerHtml = view()->make('empresa.pdfHeader',['titulo'=>'Listado de Vehículos'])->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $data = array('ordenes' => $ordenes);

        
       $pdf = PDF::loadView('livewire.vehiculos.reporte-pdf-archivo',$data)
        ->setOrientation('landscape')
        ->setOption('margin-top', '2.5cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml)
        ->setOption('footer-right', 'Página [page] de [toPage]')
        ->setOption('footer-font-size', '10')
        ->output();
        // return $pdf->download('Orden '.Carbon::now().'.pdf');

        return response()->streamDownload(
            fn () => print($pdf),
            "Orden".Carbon::now().".pdf"
       );

    }
}
