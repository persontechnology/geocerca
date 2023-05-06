<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DespachoCombustible;
use App\Models\Estacion;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DespachoCombustibleController extends Controller
{
    public function consulta(Request $request)
    {
        $user=$request->user();
        
        $data = array();
        foreach ($user->despachoCombustibles()->where('estado','Autorizado')->get() as $dc) {
            array_push(
                $data,[
                    'id'=>$dc->id,
                    'concepto'=>$dc->concepto,
                    'numero'=>$dc->numero,
                    'codigo'=>$dc->codigo,
                    'vehiculo'=>$dc->vehiculo->numero_movil_placa,
                    'fecha'=>$dc->fecha,
                    'foto'=>$dc->vehiculo->foto_link,
                    'ultimoKilometraje'=>$dc->vehiculo->ultimoKilometraje(),
                    'observaciones'=>$dc->observaciones
                ]
            );
            
        }
        return $data;
    }
    public function consultaEstaciones(Request $request)
    {
        return $request->user()->estacionServicios;
    }

    public function guardarFoto(Request $request)
    {

        $dc=DespachoCombustible::find($request->id);

        $rg_decimal="/^[0-9,]+(\.\d{0,2})?$/";

        $request->validate([
            'valorConsumido'=>'required|numeric|gt:0|lt:1000',
            'foto'=>'required|image',
            'lat'=>'required',
            'lng'=>'required',
        ]);
        
        

        try {
            DB::beginTransaction();
            
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

            $dc->detalle_mapa='Ingreso fuera de la estacion. '. 'Lat: '.$request->lat.' Lng: '.$request->lng;
            $dc->valor=$request->valorConsumido;
            $dc->valor_letras='';
            foreach (Estacion::get() as $estacion) {
                $estadoGeocerca=$estacion->query()
                ->whereContains('area', new Point($request->lat, $request->lng,))
                ->exists();
                if($estadoGeocerca){
                    $dc->detalle_mapa='Ingreso dentro de la estacion '.$estacion->nombre.'. Lat: '.$request->lat.' Lng: '.$request->lng;
                    $dc->estacion_id=$estacion->id;
                    break;
                }
            }

        
            $dc->estado='Despachado';
            $dc->fecha_despacho=Carbon::now();
            $dc->despachador_id=$request->user()->id;
            $dc->save();
            DB::commit();
            return response()->json('ok');
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json('no');
        }
    }
}
