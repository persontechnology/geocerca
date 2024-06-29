<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Lectura;
use App\Models\OrdenMovilizacion;
use App\Models\Parqueadero;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use MatanYadaev\EloquentSpatial\Objects\Point;

class GeocercaController extends Controller
{

    public function apiRestVehiculos()
    {
        $empresa=Empresa::first();
        $responseApi=Http::get($empresa->url_web_gps.'api/get_devices',[
            'user_api_hash'=>$empresa->token,
            'lang'=>'es'
        ]);

        return collect($responseApi->json('0.items', []));
    }
    public function listadoGllobal()
    {
        return $this->apiRestVehiculos();
    }
   
    public function coordenadasAutosMapa()
    {
        
       $apiResVehiculos=$this->apiRestVehiculos();
        $vehiculosRegitrados=Vehiculo::whereIn('imei',$apiResVehiculos->pluck('device_data.traccar.uniqueId'))
                            ->get()->map(function($vehiculo) use ($apiResVehiculos){
                                $vehiculosDeviceData=$apiResVehiculos->firstWhere('device_data.traccar.uniqueId',$vehiculo->imei);
                                return [[$vehiculosDeviceData['lat'],$vehiculosDeviceData['lng'],$vehiculo->placa,$vehiculo->numero_movil]];
                            });
        
        if(App::isLocal()){
            $this->coordenadasAutos();
        }
        
        return $vehiculosRegitrados;
        
    
    }

    

    public function coordenadasAutos()
    {

        $this->finalizarOrdenMovilizacion();

        $oms= OrdenMovilizacion::where(function($q){
            $q->whereBetween('fecha_salida', [Carbon::now()->startOfDay(), Carbon::now()]);
            $q->orWhereBetween('fecha_retorno', [Carbon::now(), Carbon::now()->endOfDay()]);  
        })
        ->whereNotIn('estado',['SOLICITADO','DENEGADA'])
        ->with('vehiculo')
        ->get();

        if($oms->count()>0){
            $empresa = Empresa::first();
            $minutos_extras = $empresa ? $empresa->minutos_extras_entrada_vehiculos : 0;
            $tiempo_api_rest = $empresa ? $empresa->tiempo_api_rest : 1;

            $apiResVehiculos= $this->apiRestVehiculos();

            $oms->each(function($om) use ($minutos_extras,$tiempo_api_rest, $apiResVehiculos) {
                $tiempo_salida = Carbon::parse($om->fecha_salida)->subMinutes($tiempo_api_rest)->format('Y-m-d H:i');
                $tiempo_retorno = Carbon::parse($om->fecha_retorno)->addMinutes($minutos_extras)->format('Y-m-d H:i');
                $ordenMovilizacionDentrodeRango = Carbon::now()->betweenIncluded($tiempo_salida, $tiempo_retorno);
    
                if ($ordenMovilizacionDentrodeRango && $om->vehiculo) {
                    $vehiculoDeviceData = $apiResVehiculos->firstWhere('device_data.traccar.uniqueId', $om->vehiculo->imei);
                    $this->consultarVehiculoDentroFueraGeocerca($om, $vehiculoDeviceData['lat'] ?? null, $vehiculoDeviceData['lng'] ?? null);
                }
            });
        }        

    }


    public function consultarVehiculoDentroFueraGeocerca($om,$lat,$lng)
    {
        $vehiculo=$om->vehiculo;
        $parqueadero=$vehiculo->parqueadero;
       

        $estadoGeocerca=$parqueadero->query()
        ->whereContains('area', new Point((float)$lat, (float)$lng,))
        ->exists()?'DENTRO':'FUERA';

        $tieneUltimaLectura=$om->lecturas()->latest()->first();        
        if(!$tieneUltimaLectura){
            $tieneUltimaLectura=new Lectura();
            $tieneUltimaLectura->orden_movilizacion_id=$om->id;
            $tieneUltimaLectura->estado=$estadoGeocerca;
            $tieneUltimaLectura->descripcion='VEHÍCULO INICIO '.$estadoGeocerca;
            $tieneUltimaLectura->save();
            $om->estado=$estadoGeocerca==='DENTRO'?'EJECUCIÓN DENTRO':'EJECUCIÓN FUERA';
            $om->save();
        }

        $estadoUL=$tieneUltimaLectura->estado;
      

        if($estadoGeocerca==='FUERA' && $estadoUL==='DENTRO'){
            $ultimaLectura=new Lectura();
            $ultimaLectura->orden_movilizacion_id=$om->id;
            $ultimaLectura->estado='FUERA';
            $ultimaLectura->descripcion='EL VEHICULO SALIO';
            $ultimaLectura->save();
            $om->estado='EJECUCIÓN FUERA';
            $om->save();
        }

        if($estadoGeocerca==='DENTRO' && $estadoUL==='FUERA'){
            $ultimaLectura=new Lectura();
            $ultimaLectura->orden_movilizacion_id=$om->id;
            $ultimaLectura->estado='DENTRO';
            $ultimaLectura->descripcion='EL VEHICULO INGRESO';
            $ultimaLectura->save();
            $om->estado='FINALIZADO';
            $om->save();
        }

    }

    public function finalizarOrdenMovilizacion()
    {
        $oms_ayer=OrdenMovilizacion::where('fecha_salida', '<', Carbon::now())
        ->where('fecha_retorno', '<', Carbon::now())
        ->whereNotIn('estado',['FINALIZADO','FUERA DE HORARIO','SOLICITADO'])
        ->get();
    
        if($oms_ayer->count()>0)        {
            foreach ($oms_ayer as $om) {
                $latestLectura = $om->lecturas()->latest()->first();
                
                if ($latestLectura) {
                    if ($latestLectura->estado === 'DENTRO') {
                        $om->estado = 'FINALIZADO';
                    }
                    if ($latestLectura->estado === 'FUERA') {
                        $om->estado = 'FUERA DE HORARIO';
                    }
                    $om->save();
                }else{
                    $om->estado = 'INCUMPLIDA';
                    $om->save();
                }
            }
        }
    }

    public function coordenadasParqueaderos()
    {
        $parqueadros=Parqueadero::get();

        $coordenadas = array();
        foreach ($parqueadros as $px) {
            if($px->area){
                array_push($coordenadas,$px->area->getCoordinates());
            }
        }
        return $coordenadas;
    }

    public function VerificarVigenciaOrdenMovilizacion($id)
    {
        $estado='no';
        $om=OrdenMovilizacion::findOrFail($id);
        if($om->estado=='ACEPTADA' || $om->estado==='EJECUCIÓN FUERA' || $om->estado==='EJECUCIÓN DENTRO'){
            $estado='si';
        }
        return view('movilizacion.verificarVigencia',['om'=>$om,'estado'=>$estado]);
    }

}
