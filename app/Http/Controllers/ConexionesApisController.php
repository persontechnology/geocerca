<?php

namespace App\Http\Controllers;

use App\Models\Despachador;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConexionesApisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Despachador  $despachador
     * @return \Illuminate\Http\Response
     */
    public function show(Despachador $despachador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Despachador  $despachador
     * @return \Illuminate\Http\Response
     */
    public function edit(Despachador $despachador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Despachador  $despachador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Despachador $despachador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Despachador  $despachador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Despachador $despachador)
    {
    }
    public function getConections()
    {

        $url = "http://www.ecuatracker.com/api/get_devices";
        $decodeParameters = [
            'user_api_hash' => '$2y$10$ajsbS9oqc2LfUv9/CReFPexG9ZQRD1nteRIzuztTdzynYaVOT2D2S',
            'lang' => 'es',
        ];
        try {
            $responseApi = Http::get($url, $decodeParameters);
            $responseCode = $responseApi->status();
            $response = $responseApi;
//return $response;
            if ($responseCode === 200) {
                $collectResponseApi = collect($response[0] ? $response[0]['items'] : []);
                $vehiculos = Vehiculo::get();

                $vehiclosRegitrados = [];
                foreach ($vehiculos as $vehiculo) {
                    $vehiculosDeviceData = $collectResponseApi->firstWhere('device_data.imei', $vehiculo->imei);

                    if ($vehiculosDeviceData) {
                        $data = [
                            'id' => $vehiculo->id,
                            'placa' => $vehiculo->placa,
                            'marca' => $vehiculo->marca,
                            'imei' => $vehiculosDeviceData['device_data']['imei'],
                            'ultima_conexion' => $vehiculosDeviceData['time'],
                            'lat' => $vehiculosDeviceData['lat'],
                            'lng' => $vehiculosDeviceData['lng'],
                        ];
                        array_push($vehiclosRegitrados, $data);
                    }
                }
                return $vehiclosRegitrados;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}
