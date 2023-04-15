<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parqueadero;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MatanYadaev\EloquentSpatial\Objects\Point;

class GeocercaController extends Controller
{
    public function coordenadasAutos()
    {


        $url = "http://www.ecuatracker.com/api/get_devices";
        $decodeParameters = [
            'user_api_hash' => '$2y$10$ajsbS9oqc2LfUv9/CReFPexG9ZQRD1nteRIzuztTdzynYaVOT2D2S',
            'lang' => 'es',
        ];

        $vehiclosRegitrados = [];
        try {
            $responseApi = Http::get($url, $decodeParameters);
            
            if ($responseApi->status() === 200) {
                
                $collectResponseApi = collect($responseApi[0] ? $responseApi[0]['items'] : []);
                $vehiculos = Vehiculo::get();
                
                foreach ($vehiculos as $vehiculo) {
                    $vehiculosDeviceData = $collectResponseApi->firstWhere('device_data.imei', $vehiculo->imei);
                    if ($vehiculosDeviceData) {
                        $data = [
                            [$vehiculosDeviceData['lat'],$vehiculosDeviceData["lng"],$vehiculo->placa]
                        ];
                        array_push($vehiclosRegitrados, $data);
                        $this->verificarVehiculoGeocerca($vehiculo->id,$vehiculosDeviceData['lat'],$vehiculosDeviceData['lng']);
                    }
                }
                return $vehiclosRegitrados;
            }
        } catch (\Exception $ex) {
            return [];
        }
    }


    public function verificarVehiculoGeocerca($vehiculoId,$lat,$lng)
    {
        $parqueaderos=Parqueadero::get();
        foreach ($parqueaderos as $parqueadero) {
            $existe=$parqueadero->query()
            ->whereContains('area', new Point($lat, $lng,))
            ->exists();

            if($existe){
                $vehiculo=Vehiculo::find($vehiculoId);
                $vehiculo->modelo="si esta dentrto";
                $vehiculo->save();
            }
        }
        return false;
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

}
