<?php

namespace App\Http\Controllers;

use App\DataTables\IngresoCombustible\MisIngresosCombustibleDataTable;
use App\Models\DespachoCombustible;
use App\Models\Estacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use MatanYadaev\EloquentSpatial\Objects\Point;
class IngresoCombustibleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ingreso de Combustible']);
    }

    public function index(MisIngresosCombustibleDataTable $dataTable)
    {
        return  $dataTable->render('ingresoCombustible.index');
    }


    public function ingresar($id)
    {
        $dc=DespachoCombustible::findOrFail($id);
        $this->authorize('ingresarCombustible',$dc);
        return view('ingresoCombustible.ingresar',['dc'=>$dc]);
    }

    public function pdf($id)
    {
        $despachoCombustible=DespachoCombustible::findOrFail($id);
        $this->authorize('ingresarCombustible',$despachoCombustible);
        $headerHtml = view()->make('empresa.pdfHeader',['titulo'=>'FORMULARIO AUTORIZACIÓN PARA EL DESPACHO DEL COMBUSTIBLE'])->render();
        $footerHtml = view()->make('empresa.pdfFooter')->render();
        $data = array('dc' => $despachoCombustible);

       $pdf = PDF::loadView('despachoCombustible.pdf',$data)
        // ->setOrientation('landscape')
        ->setOption('margin-top', '3cm')
        ->setOption('margin-bottom', '1cm')
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);
        return $pdf->inline('FORM-ADC- '.$despachoCombustible->numero.'.pdf');
    }


    public function guardar(Request $request)
    {
        try {
            $dc=DespachoCombustible::findOrFail($request->id);
            $this->authorize('guardarIngresoCombustible',$dc);
            $lat=$request->latitude_txt;
            $lng=$request->longitude_txt;
            $dc->valor=$request->valor;
            $dc->valor_letras=$request->valor_letras;

            $dc->detalle_mapa='Ingreso fuera de la estacion. '. 'Lat: '.$lat.' Lng: '.$lng;

            foreach (Estacion::get() as $estacion) {
                $estadoGeocerca=$estacion->query()
                ->whereContains('area', new Point($lat, $lng,))
                ->exists();
                if($estadoGeocerca){
                    $dc->detalle_mapa='Ingreso dentro de la estacion '.$estacion->nombre.'. Lat: '.$lat.' Lng: '.$lng;
                    $dc->estacion_id=$estacion->id;
                    break;
                }
            }

            if ($request->hasFile('foto')) {
                $archivo = $request->file('foto');
                 if ($archivo->isValid()) {
                    Storage::delete($dc->foto);
                    $path = Storage::putFileAs(
                        'public/dc',
                        $archivo,
                        $dc->id.'.'. $archivo->extension()
                    );
                    $dc->foto=$path;
                }
                
            }
        
            $dc->estado='Despachado';
            $dc->fecha_despacho=Carbon::now();

            $dc->despachador_id=Auth::id();
            $dc->save();
            $request->session()->flash('success','Despacho de combustible ingresado.!');
        } catch (\Throwable $th) {
            $request->session()->flash('success','Ocurrio un error, vuelva intentar.!'.$th->getMessage());
        }
        return redirect()->route('ingresoCombustible.ingresar',$dc->id);
    }
}
