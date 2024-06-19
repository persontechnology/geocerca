<?php

namespace App\Http\Controllers;

use App\DataTables\Movilizacion\OrdenMovilizacionDataTable;
use App\DataTables\OrdenMovilizacion\ConductorSolicitanteDataTable;
use App\DataTables\OrdenMovilizacion\Control\AprobarDataTable;
use App\DataTables\OrdenMovilizacion\Control\VehiculoDataTable;
use App\Http\Requests\OrdenMovilizacion\Control\RqAprobarReprobarGuardar;
use App\Models\Empresa;
use App\Models\OrdenMovilizacion;
use App\Notifications\OMInformarAceptadoNoty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class ControlOrdenMovilizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Control Orden de Movilización']);
    }
    public function index(AprobarDataTable $dataTable)
    {
        return $dataTable->render('movilizacion.control.index');
    }
    
    public function AprobarReprobar(VehiculoDataTable $dataTableVehiculo,ConductorSolicitanteDataTable $dataTableConductor,$id)
    {
        
        $orden=OrdenMovilizacion::find($id);
        $data = array('orden' => $orden,'empresa'=>Empresa::first() ,'dataTableVehiculo'=>$dataTableVehiculo,'dataTableConductor'=>$dataTableConductor);

        if (request()->get('table') == 'vehiculos') {
            return $dataTableVehiculo->render('movilizacion.control.aprobarReprobar',$data);
        }
        return $dataTableConductor->render('movilizacion.control.aprobarReprobar',$data);
    }

    public function AprobarReprobarGuardar(RqAprobarReprobarGuardar $request)
    {
        $orden=OrdenMovilizacion::find($request->id_orden_parqueadero);
        $orden->estado=$request->estado;
        $orden->autorizado_id=Auth::id();
        $orden->conductor_id=$request->conductor;
        $orden->solicitante_id=$request->solicitante;
        $orden->vehiculo_id=$request->vehiculo;
        $orden->save();
        $orden->vehiculo->conductor_id=$request->conductor;
        $orden->vehiculo->save();
        // enviar email al conductor
        if($orden->estado==='ACEPTADA' || $orden->estado==='DENEGADA'){
            
            if($orden->conductor){
                $orden->conductor->notify(new OMInformarAceptadoNoty($orden));
            }
            if($orden->solicitante){
                $orden->solicitante->notify(new OMInformarAceptadoNoty($orden));
            }
        }
        request()->session()->flash('success','Orden de movilización '.$orden->estado);
        return redirect()->route('controlOdernMovilizacionAprobarReprobar',$orden->id);

    }

    public function AprobarReprobarPdf($id)
    {
        $headerHtml = view()->make('empresa.pdfHeader')->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();

        $orden=OrdenMovilizacion::find($id);
        $data = array('orden' => $orden);

       $pdf = PDF::loadView('movilizacion.pdf',$data)
        ->setOrientation('landscape')
        ->setOption('margin-top', '2.5cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);
        return $pdf->inline('OM-'.$orden->vehiculo->numero_movil.'-'.$orden->numero.'.pdf');
    }

    public function AprobarListaGuardar(Request $request)
    {
        if($request->om){
            foreach ($request->om as $om) {
                $orden=OrdenMovilizacion::findOrFail($om);
                if($orden->estado=='SOLICITADO'){
                    $orden->estado='ACEPTADA';
                    $orden->autorizado_id=Auth::id();
                    $orden->save();
                    if($orden->conductor){
                        $orden->conductor->notify(new OMInformarAceptadoNoty($orden));
                    }
                    if($orden->solicitante){
                        $orden->solicitante->notify(new OMInformarAceptadoNoty($orden));
                    }
                }
            }
            request()->session()->flash('success','Ordenes de movilizaciones aceptadas');
        }
        
        return redirect()->route('controlOdernMovilizacion');
        
    }
}
