<?php

namespace App\Http\Requests;

use App\Models\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class RqActualizarVehiculo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       

        $regPlaca="/^([A-Z]){3}-[0-9]{4}$/";
        return [    
            'id'=>'required|exists:vehiculos,id',
            'numero_movil'=>'required|numeric|gt:0|unique:vehiculos,numero_movil,'.$this->input('id'),
            'modelo'=>'nullable|numeric|gt:0',
            'marca'=>'nullable|string|max:255',
            'placa'=>'required|string|max:255|unique:vehiculos,placa,'.$this->input('id'),
            'color'=>'nullable|string|max:255',
            'conductor'=>'nullable|exists:users,id',
            'conductorInfo'=>'nullable|string|max:255',
            'estado'=>'required|in:Activo,Inactivo',
            'descripcion'=>'nullable|string|max:255',
            'foto'=>'nullable|image',
            'tipoVehiculo'=>'required|exists:tipo_vehiculos,id',
            'imei'=>'required|string|max:255|unique:vehiculos,imei,'.$this->input('id'),
            'kilometraje'=>'nullable|numeric|gt:0',
            'parqueadero'=>'nullable|exists:parqueaderos,id'
            
        ];
    }

    public function messages()
    {
        return [
            'placa.regex'=>'Placa formato incorrecto, ingrese Ej. XAC-0111'
        ];

    }
}
