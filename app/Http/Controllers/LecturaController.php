<?php

namespace App\Http\Controllers;

use App\DataTables\LecturaDataTable;
use App\Models\Lectura;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LecturaDataTable $dataTable)
    {
        return $dataTable->render('lecturas.index');
    }

    public function eliminar(Request $request)
    {
        $tv=Lectura::find($request->id);
        try {
            $tv->delete();
            request()->session()->flash('success','Lectura eliminado');
        } catch (\Throwable $th) {
            request()->session()->flash('success','Lectura no eliminado');
        }
        return redirect()->route('lecturas');
    }

    public function editar($id)
    {
        $lec=Lectura::findOrFail($id);
        return view('lecturas.editar',['lec'=>$lec]);
    }
    public function actualizar(Request $request)
    {
        $lec=Lectura::findOrFail($request->id);
        $lec->descripcion=$request->descripcion;
        $lec->estado=$request->estado;
        $lec->save();
        $request->session()->flash('success','Lectura actualizado');
        return redirect()->route('lecturas');
    }
}
