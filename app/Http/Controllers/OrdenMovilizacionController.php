<?php

namespace App\Http\Controllers;

use App\DataTables\OrdenMovilizacion\ConductorDataTable;
use App\DataTables\OrdenMovilizacion\ConductorSolicitanteDataTable;
use App\DataTables\OrdenMovilizacion\Control\VehiculoDataTable;
use App\DataTables\OrdenMovilizacion\ListadoOrdenMovilizacionDataTable;
use App\DataTables\OrdenMovilizacion\SolicitanteDataTable;
use App\Http\Requests\RqActualizarOrdenMovilizacion;
use App\Http\Requests\RqEliminarOrdenMOvilizacion;
use App\Http\Requests\RqGuardarOrdenMovilizacion;
use App\Mail\OrdenesMovilizacionPdfVariasCorreos;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\Lectura;
use App\Models\OrdenMovilizacion;
use App\Models\Parqueadero;
use App\Models\TipoVehiculo;
use App\Models\User;
use App\Models\Vehiculo;
use App\Notifications\OMInformarAceptadoNoty;
use App\Notifications\OrdenMovilizacionIngresadaNoty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use PDF;
class OrdenMovilizacionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:Orden de Movilización']);
    }

    public function multiple(SolicitanteDataTable $dataTable,Request $request) {

        $vehiculos=Vehiculo::get();

        
        if($request->direccion) {
            $vehiculos=$vehiculos->where('direccion_id',$request->direccion);
        }
        if($request->tipoVehiculo) {
            $vehiculos=$vehiculos->where('tipo_vehiculo_id',$request->tipoVehiculo);
        }
        
        $departamentos=Departamento::get();
        $tipoVehiculos=TipoVehiculo::get();

        $today = Carbon::now();
        $nextSaturday = $today->copy()->next(Carbon::SATURDAY)->format('Y/m/d H:i');
        $nextSunday = $today->copy()->next(Carbon::SUNDAY)->format('Y/m/d H:i');
        $userEmails=User::role('Supervisor')->get()->pluck('email')->implode(',');
        
        $data = array(
            'vehiculos'=>$vehiculos,
            'proximo_sabado'=>$nextSaturday,
            'proximo_domingo'=>$nextSunday,
            'departamentos'=>$departamentos,
            'tipoVehiculos'=>$tipoVehiculos,
            'departamento'=>$request->departamento,
            'direccion'=>$request->direccion,
            'tipoVehiculo'=>$request->tipoVehiculo,
            'emailsSupervisor'=>$userEmails,
        );
        return $dataTable->render('movilizacion.multiple',$data);
    }

    public function multipleGuardar(Request $request) {
        
        $request->validate([
            'fecha_salida' => 'required|date_format:Y/m/d H:i',
            'fecha_retorno' => 'required|date_format:Y/m/d H:i',
            'estado' => 'nullable|string',
            'vehiculos' => 'required|array',
            'vehiculos.*' => 'exists:vehiculos,id',
        ]);

        $i=0;
        $omIds = [];
        foreach ($request->vehiculos as $vehiculo_id) {
            $vehiculo=Vehiculo::find($vehiculo_id);

            $orden =new OrdenMovilizacion();        
            $orden->fecha_salida=Carbon::parse($request->fecha_salida);
            $orden->fecha_retorno=Carbon::parse($request->fecha_retorno);
            $orden->numero_ocupantes=$vehiculo->numero_ocupantes??0;
            $orden->procedencia=$vehiculo->procedencia??'';
            $orden->destino=$vehiculo->destino??'';
            $orden->comision_cumplir=$vehiculo->comision_cumplir??'';
            $orden->actividad_cumplir=$vehiculo->actividad_cumplir??'';
            
            $orden->estado=$request->estado??'SOLICITADO';
            
            $orden->solicitante_id=$request->solicitante;
            $orden->vehiculo_id=$vehiculo_id;
            $orden->conductor_id=$vehiculo->conductor_id;
            
            
            $orden->user_create=Auth::user()->id;
            $orden->direccion_id=$vehiculo->direccion_id;
            $orden->save();
            
    
            if($orden->estado==='ACEPTADA'){
                $orden->autorizado_id=Auth::id();
                $orden->save();
                
                if($orden->conductor){
                    $orden->conductor->notify(new OMInformarAceptadoNoty($orden));
                }
                if($orden->solicitante){
                    $orden->solicitante->notify(new OMInformarAceptadoNoty($orden));
                }
            }
            $i++;

            array_push($omIds,$orden->id);
        }

        $this->enviarPdfPorCorreo($omIds,$request->correos);
        request()->session()->flash('success', $i.' Ordenes de movilización guardado.');
        
        return redirect()->route('odernMovilizacionListado');

    }


    public function enviarPdfPorCorreo($idsOM,$emailsUserSupervisores)
    {
        
        
        $ordenes = OrdenMovilizacion::whereIn('id', $idsOM)->get();
        
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
        $emails = explode(',', $emailsUserSupervisores);
        foreach ($emails as $email) {
            Mail::to(trim($email))->send(new OrdenesMovilizacionPdfVariasCorreos($pdfs));
        }
        
        return true;
    }


    public function index(ConductorDataTable $udt, SolicitanteDataTable $pdt) 
    {
        
        $today = Carbon::now();
        $nextSaturday = $today->copy()->next(Carbon::SATURDAY)->format('Y/m/d H:i');
        $nextSunday = $today->copy()->next(Carbon::SUNDAY)->format('Y/m/d H:i');
       


        $parqueaderos=Parqueadero::where('estado','Activo')->get();
        $ordenes=OrdenMovilizacion::whereDate('fecha_salida','>=',Carbon::now()->subMonth(2))->get();

        $userEmails=User::role('Supervisor')->get()->pluck('email')->implode(',');
        
        $data = array(
            'udt' =>$udt ,
            'pdt'=>$pdt,
            'empresa'=>Empresa::first(),
            'parqueaderos' => $parqueaderos,
            'numero'=>OrdenMovilizacion::NumeroSiguente(),
            'ordenesMovilizaciones'=>$ordenes,
            'departamentos'=>Departamento::get(),
            'proximo_sabado'=>$nextSaturday,
            'proximo_domingo'=>$nextSunday,
            'emailsSupervisor'=>$userEmails,
            
        );

        if (request()->get('table') == 'posts') {
          return $udt->render('movilizacion.calendar.index', $data);
        }
        return $pdt->render('movilizacion.calendar.index', $data);
    }

    
    public function editar(VehiculoDataTable $veht,ConductorDataTable $cont, SolicitanteDataTable $solt, $id)
    {
        $orden=OrdenMovilizacion::findOrFail($id);

        $data = array(
            'orden' => $orden,
            'veht'=>$veht,
            'cont'=>$cont,
            'solt'=>$solt,
            'departamentos'=>Departamento::get()
        );

        if (request()->get('table') == 'solicitante') {
            return $solt->render('movilizacion.editar',$data);
        }
        if (request()->get('table') == 'posts') {
            return $cont->render('movilizacion.editar',$data);
        }

        return $veht->render('movilizacion.editar',$data);
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
        $orden->actividad_cumplir=$request->actividad_cumplir;
        
        $orden->estado=$request->estado??'SOLICITADO';
        
        $orden->solicitante_id=$request->solicitante;
        $orden->vehiculo_id=$request->vehiculo;
        $orden->conductor_id=$request->conductor;
        // actualizar conductor de vehiculo
        $orden->vehiculo->conductor_id=$request->conductor;
        $orden->vehiculo->direccion_id=$request->direccion;
        $orden->vehiculo->save();

        $orden->user_create=Auth::user()->id;
        $orden->direccion_id=$request->direccion;
        $orden->save();
        

        if($orden->estado==='ACEPTADA'){
            $orden->autorizado_id=Auth::id();
            $orden->save();

            if($orden->conductor){
                $orden->conductor->notify(new OMInformarAceptadoNoty($orden));
            }
            if($orden->solicitante){
                $orden->solicitante->notify(new OMInformarAceptadoNoty($orden));
            }
        }

        if($request->correos){
            $this->enviarVariosCorreos($orden,$request->correos);
        }


        request()->session()->flash('success','Orden de movilización '.$orden->numero.' guardado');
        
        return redirect()->route('odernMovilizacionListado');

    }

    public function enviarVariosCorreos($orden,$correos) {
        $emails = explode(',', $correos);
        // Enviar el PDF a cada correo de la lista
        foreach ($emails as $correo) {
            $user=new User();
            $user->email=$correo;
            $user->name='';
            $user->password='';
            $user->notify(new OMInformarAceptadoNoty($orden));
        }
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
        $orden->actividad_cumplir=$request->actividad_cumplir;
        $orden->conductor_id=$request->conductor;
        $orden->solicitante_id=$request->solicitante;
        $orden->vehiculo_id=$request->vehiculo;
        $orden->user_update=Auth::user()->id;
        $orden->direccion_id=$request->direccion;

        if($request->estado){
            $orden->estado=$request->estado;
        }
        $orden->save();
        // actualizar conductor de vehiculo
        $orden->vehiculo->conductor_id=$request->conductor;
        $orden->vehiculo->direccion_id=$request->direccion;
        $orden->vehiculo->save();
        
        if($orden->estado==='ACEPTADA'){
            $orden->autorizado_id=Auth::id();
            $orden->save();
            if($orden->conductor){
                $orden->conductor->notify(new OMInformarAceptadoNoty($orden));
            }
            if($orden->solicitante){
                $orden->solicitante->notify(new OMInformarAceptadoNoty($orden));
            }
        }


        if($orden->estado==='DENEGADA'){
            $orden->autorizado_id=Auth::id();
            $orden->save();
            if($orden->conductor){
                $orden->conductor->notify(new OMInformarAceptadoNoty($orden));
            }
            if($orden->solicitante){
                $orden->solicitante->notify(new OMInformarAceptadoNoty($orden));
            }
        }
        
        request()->session()->flash('success','Orden de movilización '.$orden->numero.' actualizado');
        
        return redirect()->route('odernMovilizacionListado');
    }

    public function eliminar(RqEliminarOrdenMOvilizacion $request)
    {
        
        $or=OrdenMovilizacion::find($request->id);
        try {
            if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('SiteAdmin')){
                $or->lecturas()->delete();
                $or->delete();
                request()->session()->flash('success','Ordén de movilización eliminado exitosamente');
            }else{
                request()->session()->flash('info','NO ELIMINADO, porque solo usuario SuperAdmin y SiteAdmin, pueden eliminar O.M');
            }
            
        } catch (\Throwable $th) {
            request()->session()->flash('info','Ordén de movilización no eliminado, porque contiene información relacionado con otro modulos.');
        }
        return redirect()->route('odernMovilizacionListado');
    }

    public function obtener(Request $request)
    {
        $orden=OrdenMovilizacion::with(['vehiculo','vehiculo.tipoVehiculo','conductor','solicitante','vehiculo.direccion'])->find($request->id);
        return $orden;
    }

    public function listado(ListadoOrdenMovilizacionDataTable $dataTable)
    {
        return $dataTable->render('movilizacion.calendar.listado');
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
        return $pdf->inline('OM-'.$orden->vehiculo->numero_movil.'-'.$orden->numero.'.pdf');
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
