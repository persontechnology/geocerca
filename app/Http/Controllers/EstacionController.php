<?php

namespace App\Http\Controllers;

use App\DataTables\EstacionDataTable;
use App\Models\Estacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;


class EstacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Estación de servicios']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EstacionDataTable $dataTable)
    {
       return $dataTable->render('estacion.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('estacion.crear');
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


    public function store(Request $request)
    {

        $request->validate([
            'nombre'=>'required|string|max:255|unique:estacions,nombre',
            'area'    => 'required'
        ]);
        
        try {
            DB::beginTransaction();
            $estacion=new Estacion();
            $estacion->nombre=$request->nombre;
            $estacion->area=new Polygon([
                new LineString($this->generarCoordenadas($request->area))
            ]);
            $estacion->save();
            
            DB::commit();
            request()->session()->flash('success','Estación ingresada');
        } catch (\Throwable $th) {
            request()->session()->flash('success','Ocurrio un error, vuelva intentar o consulte con administrador');
            DB::rollback();
        }
        return redirect()->route('estacion.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estacion  $estacion
     * @return \Illuminate\Http\Response
     */
    public function show(Estacion $estacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estacion  $estacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Estacion $estacion)
    {
        $data = array(
            'estacion'=>$estacion,
            'area'=>$estacion->area?$estacion->area->getCoordinates():[],
        );
        return view('estacion.editar',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estacion  $estacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estacion $estacion)
    {
        $request->validate([
            'nombre'=>'required|string|max:255|unique:estacions,nombre,'.$estacion->id,
            'area'    => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            $estacion->nombre=$request->nombre;
            
            if($request->area){
                $estacion->area=new Polygon([
                    new LineString($this->generarCoordenadas($request->area))
                ]);
            }
            $estacion->save();
            DB::commit();
            request()->session()->flash('success','Estación actualizado');
        } catch (\Throwable $th) {
            request()->session()->flash('success','Ocurrio un error, vuelva intentar o consulte con administrador');
            DB::rollback();
        }
        return redirect()->route('estacion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estacion  $estacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estacion $estacion)
    {
        try {
            DB::beginTransaction();
            $estacion->delete();
            DB::commit();
            request()->session()->flash('success','Estación eliminado');
            
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('danger','Ocurrio un error, vuelva intentar o consulte con administrador');
        }
        return redirect()->route('estacion.index');
    }
}
