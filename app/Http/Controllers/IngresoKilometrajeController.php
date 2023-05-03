<?php

namespace App\Http\Controllers;

use App\DataTables\Usuarios\Guardia\IngresarKilometrajeDataTable;
use App\Http\Controllers\Api\GeocercaController;
use App\Http\Requests\Usuarios\Guardia\RqGuardarKilometraje;
use App\Models\Empresa;
use App\Models\Kilometraje;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
class IngresoKilometrajeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ingreso de Kilometraje']);
    }

    // DEivid, se obtiene le listado de vehiculos del controlador de geocerca
    public function obtenerListadoVehiculos()
    {
        $vehiculos=new GeocercaController();
        return $vehiculos->apiRestVehiculos();
    }

    public function ingresarKilometraje(IngresarKilometrajeDataTable $dataTable)
    {
        // $ve=Vehiculo::where('imei','007316427')->first();
        
        $user=Auth::user();
        $data = array('parqueaderos' => $user->parqueaderos );
        return $dataTable->render('ingresoKilometraje.ingresar',$data);
    }


    public function obtenerKilometrajeVehiculo($ve)
    {
        try {
            $empresa=Empresa::first();
            $lista_vehiculos= $this->obtenerListadoVehiculos();
            $vehiculosDeviceData=$lista_vehiculos->firstWhere('device_data.imei',$ve->imei);

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


    public function actualizarKilometrajeEcuatrack($ve,$kilometraje)
    {   
        try {
            
            $empresa=Empresa::first();
            $lista_vehiculos= $this->obtenerListadoVehiculos();
            $vehiculosDeviceData=$lista_vehiculos->firstWhere('device_data.imei',$ve->imei);

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

    public function guardarKilometraje(RqGuardarKilometraje $request)
    {
        $ve=Vehiculo::findOrFail($request->vehiculo);
        try {
            DB::beginTransaction();
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
            $km->vehiculo_id=$request->vehiculo;
            $km->user_create=Auth::id();
            $km->save();

            $ve->parqueadero_id=$request->parqueadero;
            $ve->save();

            DB::commit();
            $request->session()->flash('success','Nuevo kilometraje ingresado al vehículo '.$ve->numero_movil_placa.' exitoso.!');
            return redirect()->route('ingresoKilometraje.ingresar');
        } catch (\Throwable $th) {
            DB::rollBack();
            $request->session()->flash('danger','Ocurrio un error, vuelva intentar.! '.$th->getMessage());
            return redirect()->back()->withInput();
        }

        
    }
}
