<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Empresa']);
    }
    public function index()
    {
        $empresa = Empresa::first();
        return view('empresa.index', ['empresa' => $empresa]);
    }

    public function actualizar(Request $request)
    {


        if (Auth::user()->hasRole('SuperAdmin')) {
            $request->validate([
                'fecha_caducidad_inicio'=>'required',
                'fecha_caducidad_fin'=>'required|date|after_or_equal:fecha_caducidad_inicio',
                'estado'=>'required|in:Activo,Inactivo',
            ]); 
        }

        $request->validate([
            'tipo'=>'required|in:Pública,Privada',
            'nombre'=>'required|string|max:255',
            'descripcion'=>'required|string|max:255',
            'foto'=>'nullable|image',
            'token'=>'nullable|string|max:225',
            'codigo'=>'required|string|max:255',
            'version'=>'required|string|max:255',
            'norma'=>'required|string|max:255',
            'minutos_extras_entrada_vehiculos'=>'required|integer|between:0,60',
            'tiempo_api_rest'=>'required|integer|between:1,60',
            'fecha'=>'required|date'
        ]); 

        $empresa=Empresa::first();
        $empresa->tipo=$request->tipo;
        $empresa->nombre=$request->nombre;
        $empresa->descripcion=$request->descripcion;
        $empresa->token=$request->token;
        $empresa->codigo=$request->codigo;
        $empresa->version=$request->version;
        $empresa->norma=$request->norma;
        $empresa->codigo_tarjeta_vehiculo_invitado='';
        $empresa->minutos_extras_entrada_vehiculos=$request->minutos_extras_entrada_vehiculos;
        $empresa->tiempo_api_rest=$request->tiempo_api_rest;
        $empresa->fecha=$request->fecha;

        if ($request->hasFile('foto')) {
            $archivo = $request->file('foto');
            if ($archivo->isValid()) {
                Storage::delete($empresa->logo);
                $path = Storage::putFileAs(
                    'public/empresa',
                    $archivo,
                    $empresa->id . '.' . $archivo->extension()
                );
                $empresa->logo = $path;
            }
        }
        if (Auth::user()->hasRole('SuperAdmin')) {
            $empresa->fecha_caducidad_inicio = $request->fecha_caducidad_inicio;
            $empresa->fecha_caducidad_fin = $request->fecha_caducidad_fin;
            $empresa->estado = $request->estado;
            $empresa->url_web_gps = $request->url_web_gps;
            $empresa->token = $request->token;
        }
        $empresa->user_update = Auth::user()->id;
        $empresa->save();
        request()->session()->flash('success', 'Empresa actualizado');
        return redirect()->route('empresa');
    }
}
