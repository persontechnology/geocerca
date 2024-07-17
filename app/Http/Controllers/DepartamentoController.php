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
            'departamentos'=>Direccion::get()
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
            'departamentos'=>Direccion::get()
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
        $direccion=Departamento::create($request->all());
        return redirect()->route('departamentos.index')->with('success','Departamento ingresado.');
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
            'departamentos'=>Direccion::get(),
            'direccion'=>Departamento::findOrFail($direccionId)
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
        $direccion=Departamento::findOrFail($direccionId);
        $direccion->update($request->all());
        return redirect()->route('departamentos.index')->with('success','Departamento actualizado.!');
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

    public function eliminarDepartamento(Request $request) {
        try {
            $departamento=Departamento::destroy($request->id);
            return redirect()->route('departamentos.index')->with('success','Departamento eliminado.!');
        } catch (\Throwable $th) {
            return redirect()->route('departamentos.index')->with('info','Departamento no eliminado.!');
        }
    }



   
}
