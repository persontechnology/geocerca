<?php

namespace App\Http\Controllers;

use App\DataTables\ParqueaderoDataTable;
use App\Http\Requests\Parqueaderos\RqActualizar;
use App\Http\Requests\Parqueaderos\RqGuardar;
use App\Models\Parqueadero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;


class ParqueaderoController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['permission:Parqueaderos']);
    }

    public function index(ParqueaderoDataTable $dataTable)
    {
        return $dataTable->render('parqueaderos.index');
    }
    public function nuevo()
    {
        $guardias = User::where('estado','Activo')->role('Guardia')->get();
        return view('parqueaderos.nuevo', ['guardias' => $guardias]);
    }

    public function generarCoordenadas($area)
    {
        $pattern = "/(\((?:[^()]++|(?1))*\))(*SKIP)(*F)|,/";
        $coordenadas=preg_split($pattern, $area);
        
        $contador=0;
        $ultimo_array = array();
        $nuevas_coordenadas = array();
        foreach ($coordenadas as $coor) {
            
            $latlon_data = explode('(' , rtrim($coor, ')'));    
            $latlon=explode(',',$latlon_data[1]);
            array_push($nuevas_coordenadas,  new Point(floatval($latlon[0]), floatval($latlon[1])));

            if($contador===0){
                $ultimo_array=new Point(floatval($latlon[0]), floatval($latlon[1]));
            }
            $contador++;
        }
         array_push($nuevas_coordenadas,$ultimo_array);
         return $nuevas_coordenadas;

    }
    public function guardar(RqGuardar $request)
    {
        
        try {
            DB::beginTransaction();
            $parqueadero = new Parqueadero();
            $parqueadero->nombre = $request->nombre;
            $parqueadero->descripcion = $request->descripcion;
            $parqueadero->area=new Polygon([
                new LineString($this->generarCoordenadas($request->area))
            ]);
            $parqueadero->user_create = Auth::user()->id;
            $parqueadero->save();
            $parqueadero->guardias()->sync($request->guardias);
            DB::commit();
            request()->session()->flash('success', 'Parqueadero creado');
            return redirect()->route('parqueaderos');
        } catch (\Exception $e) {
            DB::rollback();
            request()->session()->flash('danger', $e);
            return redirect()->route('parqueaderos');
        }
    }

    public function editar($id)
    {
        $parqueadero = Parqueadero::find($id);
        $guardias = $guardias = User::where('estado','Activo')->role('Guardia')->get();
        $existe=Parqueadero::query()->whereContains('area', new Point(-1.0430571679279186, -78.59103092302097))->exists();
        
        $data = array(
            'parqueadero' => $parqueadero, 'guardias' => $guardias,
            'area'=>$parqueadero->area?$parqueadero->area->getCoordinates():[],
            'existe'=>$existe
        );
        
        return view('parqueaderos.editar', $data);
    }
    public function actualizar(RqActualizar $request)
    {
        try {
            DB::beginTransaction();
            $parqueadero = Parqueadero::find($request->id);
            $parqueadero->nombre = $request->nombre;
            $parqueadero->descripcion = $request->descripcion;
            if($request->area){
                $parqueadero->area=new Polygon([
                    new LineString($this->generarCoordenadas($request->area))
                ]);
            }
            $parqueadero->user_update = Auth::user()->id;
            $parqueadero->save();
            $parqueadero->guardias()->sync($request->guardias);
            DB::commit();
            request()->session()->flash('success', 'Parqueadero actualizado');
            return redirect()->route('parqueaderos');
        } catch (\Exception $e) {
            DB::rollback();
            request()->session()->flash('danger', 'Ocurrio un error, consulte con administrador o vuelva intentar.');
            return redirect()->route('parqueaderoEditar', $request->id);
        }
    }

    

    public function eliminar(Request $request)
    {
        $request->validate([
            'id'=>'required|exists:parqueaderos,id'
        ]);
        $parqueadero=Parqueadero::find($request->id);
        try {
            DB::beginTransaction();
            $parqueadero->delete();
            DB::commit();
            request()->session()->flash('success','Parqueadero eliminado.');
        } catch (\Throwable $th) {
            request()->session()->flash('danger','No se puede eliminar '.$parqueadero->nombre.' ya que contiene información relacionada.');
            DB::rollback();
        }
        return redirect()->route('parqueaderos');
    }
}
