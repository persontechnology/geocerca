<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Kilometraje;
use App\Models\Vehiculo;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Http\Request as HttpRequest;

class IngresoKilometrajeController extends Controller
{
    public function ingresar(HttpRequest $request)
    {
        

        $request->validate([
            'parqueadero'=>'required',
            'ingreso'=>'required',
            'vehiculo'=>'required',
            'kilometraje'=>'required_if:ingreso,SI',
        ]);


        $ve=Vehiculo::where(function($q) use($request){
          return  $q->where('placa',$request->vehiculo)->orWhere('numero_movil',$request->vehiculo);
        })->first();
        
        if($ve){


            if($request->ingreso==='SI'){
                if(intval($request->kilometraje)>intval($ve->ultimoKilometraje())){
                    $this->crearKilometraje($request,$ve);
                    return json_encode(['message'=>'si']);
                }else{
                    return json_encode(['message'=>'km','msg'=>'El kilometraje debe ser mayor a '.$ve->ultimoKilometraje()]);
                }
            }else{
                
                $this->crearKilometraje($request,$ve);
                return json_encode(['message'=>'si']);
            }
            
            
        }else{
            return json_encode(['message'=>'no']);
        }

    }


    public function crearKilometraje($request,$ve)
    {
        
        try {
            $km=new Kilometraje();

            if($request->ingreso==='SI'){
                $km->detalle='Ingreso con kilometraje y se actualizo en ECUATRACK';
                $km->numero=$request->kilometraje; // ACTUALIZAR EN ECUATRACK
                $this->actualizarKilometrajeEcuatrack($ve,$request->kilometraje);

            }else{
                $km->detalle='Ingreso sin kilometraje, pero se actualizo con el último kilometraje de ECUATRACK.';
                $km->numero=$this->obtenerKilometrajeVehiculo($ve);  /// CONSULTAR DE ECUATRACK Y ACTUALIZAR EN GEOCERCA
            }
            
            $km->llenado=$request->ingreso;
            $km->parqueadero_id=$request->parqueadero;
            $km->vehiculo_id=$ve->id;
            $km->user_create=$request->user()->id;
            $km->save();

            $ve->parqueadero_id=$request->parqueadero;
            $ve->save();
            return $km;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

    public function obtenerKilometrajeVehiculo($ve)
    {
        try {
            $empresa=Empresa::first();
            $lista_vehiculos= $this->obtenerListadoVehiculos();
            $vehiculosDeviceData=$lista_vehiculos->firstWhere('device_data.traccar.uniqueId',$ve->imei);

            $responseApiSensor=Http::get($empresa->url_web_gps.'api/edit_device_data',[
                'user_api_hash'=>$empresa->token,
                'lang'=>'es',
                'device_id'=>$vehiculosDeviceData['id']
            ]);
            $lista_sensor= collect($responseApiSensor->json('sensors.data', []));
            $lista_sensorData=$lista_sensor->firstWhere('type','odometer');

            return $lista_sensorData['odometer_value'];
        } catch (\Throwable $th) {
            return $ve->ultimoKilometraje();
        }
    }

    

    public function misEstaciones(HttpRequest $request)
    {
        return $request->user()->parqueaderos;
    }

    public function obtenerListadoVehiculos()
    {
        $vehiculos=new GeocercaController();
        return $vehiculos->apiRestVehiculos();
    }

    public function actualizarKilometrajeEcuatrack($ve,$kilometraje)
    {   
        try {
            
            $empresa=Empresa::first();
            $lista_vehiculos= $this->obtenerListadoVehiculos();
            $vehiculosDeviceData=$lista_vehiculos->firstWhere('device_data.traccar.uniqueId',$ve->imei);

            $responseApiSensor=Http::get($empresa->url_web_gps.'api/edit_device_data',[
                'user_api_hash'=>$empresa->token,
                'lang'=>'es',
                'device_id'=>$vehiculosDeviceData['id']
            ]);
            
            $lista_sensor= collect($responseApiSensor->json('sensors.data', []));
            $lista_sensorData=$lista_sensor->firstWhere('type','odometer');
            
            $sensor_id=$lista_sensorData['id'];

            $data = array(
                'device_id'=>$lista_sensorData['device_id'],
                'sensor_type'=>$lista_sensorData['type'],
                'sensor_name'=>$lista_sensorData['name'],
                'unit_of_measurement'=>$lista_sensorData['unit_of_measurement'],
                'tag_name'=>$lista_sensorData['tag_name'],
                'odometer_value_by'=>$lista_sensorData['odometer_value_by'],
                'odometer_value'=>$kilometraje,
                'odometer_value_unit'=>$lista_sensorData['odometer_value_unit'],
            );

            $client = new Client();
            
            $request = new Request(
                'POST', 
                $empresa->url_web_gps.'api/edit_sensor?lang=en&user_api_hash='.$empresa->token.'&sensor_id='.$sensor_id, 
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            );
            $res = $client->sendAsync($request, ['form_params' => $data])->wait();
            return json_decode($res->getBody()); // retorno 1, si todo ook

        } catch (\Throwable $th) {
            return 0; //retorno 0 si fallo
        }
    }

}
