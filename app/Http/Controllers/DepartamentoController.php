<?php

namespace App\Http\Controllers;

use App\DataTables\DepartamentoDataTable;
use App\Models\Departamento;
use App\Models\Direccion;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Direcciones & Departamentos']);
    }

    public function index(DepartamentoDataTable $dataTable)
    {
        $data = array(
            'departamentos'=>Departamento::get()
        );
        return $dataTable->render('departamentos.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'departamentos'=>Departamento::get()
        );
        return view('departamentos.nuevo',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Departamento::create($request->all());
        return redirect()->route('direcciones-departamentos.index')->with('success','Departamento ingresado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function show(Departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function edit($direccionId)
    {
        
        $data = array(
            'departamentos'=>Departamento::get(),
            'direccion'=>Direccion::findOrFail($direccionId)
        );
        return view('departamentos.editar',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $direccionId)
    {
        $direccion=Direccion::findOrFail($direccionId);
        $direccion->update($request->all());
        return redirect()->route('direcciones-departamentos.index')->with('success','Departamento actualizado.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Departamento $departamento)
    {
        //
    }

    public function eliminarDireccion(Request $request) {
        try {
            $departamento=Direccion::destroy($request->id);
            return redirect()->route('direcciones-departamentos.index')->with('success','Dirección eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('direcciones-departamentos.index')->with('info','Dirección no eliminado.!');
        }
    }

    public function eliminarDepartamento(Request $request) {
        try {
            $departamento=Departamento::destroy($request->id);
            return redirect()->route('direcciones-departamentos.index')->with('success','Departamento eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('direcciones-departamentos.index')->with('info','Departamento no eliminado.!');
        }
    }

    public function guardar(Request $request)  {
        $direccion=Direccion::create($request->all());
        return redirect()->route('direcciones-departamentos.index')->with('success','Dirección ingresado.');
    }

   
}
