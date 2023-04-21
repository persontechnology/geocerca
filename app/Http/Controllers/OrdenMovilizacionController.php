<?php

namespace App\Http\Controllers;

use App\DataTables\OrdenMovilizacion\ConductorSolicitanteDataTable;
use App\DataTables\OrdenMovilizacion\Control\VehiculoDataTable;
use App\DataTables\OrdenMovilizacion\ListadoOrdenMovilizacionDataTable;
use App\Http\Requests\RqActualizarOrdenMovilizacion;
use App\Http\Requests\RqEliminarOrdenMOvilizacion;
use App\Http\Requests\RqGuardarOrdenMovilizacion;
use App\Models\Empresa;
use App\Models\Lectura;
use App\Models\OrdenMovilizacion;
use App\Models\Parqueadero;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
class OrdenMovilizacionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:Orden de Movilización']);
    }

    public function index(ConductorSolicitanteDataTable $dataTable)
    {
        $parqueaderos=Parqueadero::where('estado','Activo')->get();
        $ordenes=OrdenMovilizacion::whereDate('fecha_salida','>=',Carbon::now()->subMonth(2))->get();
        $data = array(
            'empresa'=>Empresa::first(),
            'parqueaderos' => $parqueaderos,
            'numero'=>OrdenMovilizacion::NumeroSiguente(),
            'ordenesMovilizaciones'=>$ordenes
        );
        return $dataTable->render('movilizacion.calendar.index',$data);
        // return $dataTable->render('movilizacion.index');
    }
    
    public function guardar(RqGuardarOrdenMovilizacion $request)
    {
        $orden =new OrdenMovilizacion();        
        $orden->fecha_salida=Carbon::parse($request->fecha_salida);
        $orden->fecha_retorno=Carbon::parse($request->fecha_retorno);
        $orden->numero_ocupantes=$request->numero_ocupantes;
        $orden->procedencia=$request->procedencia;
        $orden->destino=$request->destino;
        $orden->comision_cumplir=$request->comision_cumplir;
        $orden->estado='SOLICITADO';
        
        $orden->solicitante_id=$request->solicitante;
        $orden->vehiculo_id=$request->vehiculo;
        $orden->conductor_id=$request->conductor;
        // actualizar conductor de vehiculo
        $orden->vehiculo->conductor_id=$request->conductor;
        $orden->vehiculo->save();

        $orden->user_create=Auth::user()->id;
        $orden->save();
        
        request()->session()->flash('success','Orden de movilización '.$orden->numero.' guardado');
        
        return redirect()->route('odernMovilizacionListado');

    }

    // RqActualizarOrdenMovilizacion
    public function actualizar(RqActualizarOrdenMovilizacion $request)
    {
        

        $orden =OrdenMovilizacion::find($request->id_orden_parqueadero);
        $orden->fecha_salida=Carbon::parse($request->fecha_salida);
        $orden->fecha_retorno=Carbon::parse($request->fecha_retorno);
        $orden->numero_ocupantes=$request->numero_ocupantes;
        $orden->procedencia=$request->procedencia;
        $orden->destino=$request->destino;
        $orden->comision_cumplir=$request->comision_cumplir;
        $orden->conductor_id=$request->conductor;
        $orden->solicitante_id=$request->solicitante;
        $orden->vehiculo_id=$request->vehiculo;
        $orden->user_update=Auth::user()->id;
        if($request->estado){
            $orden->estado=$request->estado;
        }
        $orden->save();
        // actualizar conductor de vehiculo
        $orden->vehiculo->conductor_id=$request->conductor;
        $orden->vehiculo->save();
        
        request()->session()->flash('success','Orden de movilización '.$orden->numero.' actualizado');
        
        return redirect()->route('odernMovilizacionListado');
    }

    public function eliminar(RqEliminarOrdenMOvilizacion $request)
    {
        
        $or=OrdenMovilizacion::find($request->id);
        try {
            $or->delete();
            
        } catch (\Throwable $th) {
            request()->session()->flash('success','Ordén de movilización no eliminado');
        }
        return redirect()->route('odernMovilizacionListado');
    }

    public function obtener(Request $request)
    {
        $orden=OrdenMovilizacion::with(['vehiculo','vehiculo.tipoVehiculo','conductor','solicitante'])->find($request->id);
        return $orden;
    }

    public function listado(ListadoOrdenMovilizacionDataTable $dataTable)
    {
        return $dataTable->render('movilizacion.calendar.listado');
    }

    // public function AprobarReprobar(VehiculoDataTable $dataTableVehiculo,ConductorSolicitanteDataTable $dataTableConductor,$id)
    public function editar(VehiculoDataTable $dataTableVehiculo,ConductorSolicitanteDataTable $dataTableConductor, $id)
    {
        $orden=OrdenMovilizacion::findOrFail($id);

        $data = array(
            'orden' => $orden,
            'dataTableVehiculo'=>$dataTableVehiculo,
            'dataTableConductor'=>$dataTableConductor
        );

        if (request()->get('table') == 'vehiculos') {
            return $dataTableVehiculo->render('movilizacion.editar',$data);
        }
        return $dataTableConductor->render('movilizacion.editar',$data);
    }

    public function pdf($id)
    {
        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $orden=OrdenMovilizacion::findOrFail($id);
        $data = array('orden' => $orden);

       $pdf = PDF::loadView('movilizacion.pdf',$data)
        ->setOrientation('landscape')
        ->setOption('margin-top', '2.5cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);
        return $pdf->inline('Orden '.$orden->numero.'.pdf');
    }

    public function lecturas($id)
    {
        $orden=OrdenMovilizacion::findOrFail($id);
        return view('movilizacion.lecturas',['orden'=>$orden]);
    }

    public function lecturaActualizar(Request $request)
    {
        $lec=Lectura::findOrFail($request->id);
        $lec->estado=$request->estado;
        $lec->descripcion=$request->descripcion;
        $lec->save();
        request()->session()->flash('success', 'Lectura actualizado');
        return redirect()->route('odernMovilizacionLecturas',$lec->orden_movilizacion_id);
    }
    public function reportePdf()
    {
        return view('movilizacion.reportePdf');
    }
}
