<?php

namespace App\Http\Controllers;

use App\DataTables\DireccionDataTable;
use App\Models\Direccion;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Direcciones & Departamentos']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DireccionDataTable $dataTable)
    {
        return $dataTable->render('direcciones.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('direcciones.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Direccion::create($request->all());
        return redirect()->route('direcciones.index')->with('success','Dirección ingresado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $direccion=Direccion::findOrFail($id);
        return view('direcciones.editar',['direccion'=>$direccion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $direccion=Direccion::findOrFail($id);
        $direccion->update($request->all());
        return redirect()->route('direcciones.index')->with('success','Dirección actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function eliminarDireccion(Request $request) {
        try {
            $departamento=Direccion::destroy($request->id);
            return redirect()->route('direcciones.index')->with('success','Dirección eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('direcciones.index')->with('info','Dirección no eliminado.!');
        }
    }
}
