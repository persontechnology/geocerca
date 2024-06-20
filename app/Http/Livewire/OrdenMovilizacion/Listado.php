<?php

namespace App\Http\Livewire\OrdenMovilizacion;

use App\Mail\OrdenesMovilizacionPdfVariasCorreos;
use App\Models\Departamento;
use App\Models\Direccion;
use App\Models\OrdenMovilizacion;
use App\Models\Parqueadero;
use App\Models\TipoVehiculo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PDF;
use Livewire\Component;
use Livewire\WithPagination;
class Listado extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    // para filtros
    public $IdTipoVehiculo;
    public $EstadoOrdenMovilizacion;
    public $NumeroOrden;
    public $IdParqueadero;
    public $desde;
    public $hasta;
    public $mostrar='10';

    public $selecionados=[];
    public $selectAll = false;
    public $currentPageIds = [];

     // Nuevas propiedades para manejar departamentos y direcciones
     public $departamento_id;
     public $direccion_id;
     public $correo_destino;
     public $enviandoEmails = false; // Propiedad para manejar el estado de envío


    // querys
    protected $queryString = [
        'NumeroOrden' => ['except' => '','as'=>'orden'],
        'IdTipoVehiculo'=>['except' => '','as'=>'tipovehiculo'],
        'EstadoOrdenMovilizacion'=>['except' => '','as'=>'estado'],
        'IdParqueadero'=>['except' => '','as'=>'parqueadero'],
        'desde'=>['except' => '','as'=>'fi'],
        'hasta'=>['except' => '','as'=>'ff'],
        'mostrar'=>['except'=>'10']
    ];


    public function mount()
    {
        // Obtén los correos de los supervisores y establece el valor inicial de correo_destino
        $this->correo_destino = User::role('Supervisor')->get()->pluck('email')->implode(',');
    }

    public function render()
    {
        $data = array(
            'ordenMovilizaciones'=>$this->listadoOrdenes(),
            'tipoVehiculos'=>TipoVehiculo::get(),
            'parqueaderos'=>Parqueadero::get(),
            'departamentos' => Departamento::all(), // Obtener todos los departamentos
            'direcciones' => [], // Esto se actualizará dinámicamente según la selección del departamento
            
        );

        // Si hay un departamento seleccionado, obtener sus direcciones
        if ($this->departamento_id) {
            $data['direcciones'] = Direccion::where('departamento_id', $this->departamento_id)->get();
        }

        return view('livewire.orden-movilizacion.listado',$data);
    }

    public function listadoOrdenes(){
        

        $ordenMovilizaciones=OrdenMovilizacion::where(function ($query)  {
            
            if($this->desde && $this->hasta){
                $query->whereDate('fecha_salida','>=', $this->desde);
                $query->whereDate('fecha_retorno','<=', $this->hasta);
            }
            if($this->NumeroOrden){
                $query->where('numero', 'like', '%'.$this->NumeroOrden.'%');
            }
            if($this->EstadoOrdenMovilizacion){
                $query->where('estado','like','%'.$this->EstadoOrdenMovilizacion.'%');
            }
            // Agregar filtro por direccion_id si está seleccionado
            if ($this->direccion_id) {
                $query->where('direccion_id', $this->direccion_id);
            }
        })
        ->whereHas('vehiculo',function($query) {
            $query->whereRaw("tipo_vehiculo_id like ?",["%{$this->IdTipoVehiculo}%"]);
        })->latest()->paginate($this->mostrar);
        
         // Actualiza los IDs de la página actual
         $this->currentPageIds = $ordenMovilizaciones->pluck('id')->toArray();

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

    public function updatedEstadoOrdenMovilizacion($value)
    {
        $this->EstadoOrdenMovilizacion=$value;
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
        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $data = array('ordenes' => $ordenes);

        
       $pdf = PDF::loadView('livewire.orden-movilizacion.listadoPfd',$data)
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

    public function pdfSelecionados(){
        $ordenes=OrdenMovilizacion::whereIn('id',$this->selecionados)->get();
        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $data = array('ordenes' => $ordenes);

        
       $pdf = PDF::loadView('livewire.orden-movilizacion.listadoPfd',$data)
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

    public function pdfSelecionadosADetalle(){
        $ordenes=OrdenMovilizacion::whereIn('id',$this->selecionados)->get();
        
        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

       $pdfs = PDF::loadView('livewire.orden-movilizacion.multipdfs', ['ordenes' => $ordenes])
       ->setOrientation('landscape')
        ->setOption('margin-top', '2.5cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml)
        ->setOption('footer-right', 'Página [page] de [toPage]')
        ->setOption('footer-font-size', '10')
        ->output();

        return response()->streamDownload(
            fn () => print($pdfs),
            "Orden".Carbon::now().".pdf"
       );
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selecionados = array_merge($this->selecionados, $this->currentPageIds);
        } else {
            $this->selecionados = array_diff($this->selecionados, $this->currentPageIds);
        }
    }

     // Métodos para actualizar las direcciones basadas en el departamento seleccionado
     public function updatedDepartamentoId($value)
     {
         $this->direccion_id = null; // Reiniciar la selección de dirección al cambiar departamento
     }
     public function getDireccionesProperty()
     {
         if ($this->departamento_id) {
             return Direccion::where('departamento_id', $this->departamento_id)->get();
         } else {
             return collect(); // Si no hay departamento seleccionado, devolver una colección vacía o null según sea necesario.
         }
     }
     

    public function enviarPdfPorCorreo()
    {
        $this->enviandoEmails = true; // Activar el estado de envío

        $ordenes = OrdenMovilizacion::whereIn('id', $this->selecionados)->get();
        
        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $pdfs = PDF::loadView('livewire.orden-movilizacion.multipdfs', ['ordenes' => $ordenes])
            ->setOrientation('landscape')
            ->setOption('margin-top', '2.5cm')
            ->setOption('margin-bottom', '1cm')
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setOption('footer-right', 'Página [page] de [toPage]')
            ->setOption('footer-font-size', '10')
            ->output();

        // Enviar el PDF por correo
        $emails = explode(',', $this->correo_destino);
        foreach ($emails as $email) {
            Mail::to(trim($email))->send(new OrdenesMovilizacionPdfVariasCorreos($pdfs));
        }
        $this->enviandoEmails = false; // Desactivar el estado de envío
        $this->correo_destino='';
        session()->flash('messageEmail', 'PDF enviado con éxito a los correos especificados.');
    }


}
