<?php

namespace App\Http\Requests\Usuarios\Guardia;

use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RqGuardarKilometraje extends FormRequest
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
        
        return [
            'vehiculo'=>'required|exists:vehiculos,id',
            'ingreso'=>'required|in:SI,NO',
            'kilometraje'=> [
                'required_if:ingreso,SI',
                'nullable',
                'numeric',
                'gt:'.$this->request->get('ultimoKilometraje'),
            ],
            'ultimoKilometraje'=>'required_if:ingreso,SI|nullable|numeric|gt:0',
            'parqueadero'=>'required|exists:parqueaderos,id',
            'numeroMovil'=>'nullable',
            'marca'=>'nullable',
            'modelo'=>'nullable',
            'placa'=>'nullable',
            'tipo'=>'nullable',
            'color'=>'nullable',
            'conductor'=>'nullable',
            'conductor_info'=>'nullable',
        ];
    }
}
