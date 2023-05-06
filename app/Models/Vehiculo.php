<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vehiculo extends Model
{
    use HasFactory;

    // Deivid, un vehiculo tiene tipo de vehiculo
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class,'tipo_vehiculo_id');
    }
    
    public function kilometrajes()
    {
        return $this->hasMany(Kilometraje::class);
    }

    public function ultimoKilometraje()
    {
        return $this->hasMany(Kilometraje::class)->latest()->first()->numero??0;
    }

    // Deivid, numero movil y placa del vehiculo concatenados
    public function getNumeroMovilPlacaAttribute()
    {
            return $this->numero_movil.' '.$this->placa;
    }
    
    //DEivid, funcion para retornar color segun a lso estado
    public function getColorEstadoAttribute()
    {   
        $color='border-success';
        switch ($this->estado) {
            case 'Activo':
                $color='primary';
                break;
            case 'Inactivo':
                $color='secondary';
                break;
            case 'Presente':
                $color='success';
                break;
            case 'Ausente':
                $color='danger';
                break;
        }
        return $color;
    }

    // Deivid, un vehiculo tiene un conductor
    public function conductor()
    {
        return $this->belongsTo(User::class,'conductor_id');
    }

    // Deivid, id de conductor si existe concatenados
    public function getIdConductorAttribute()
    {
        if($this->conductor){
            return $this->conductor->id;
        }
        return '';
    }

    // deivid un vehiculo tiene ordenes de movilizacion
    public function ordenesMovilizaciones()
    {
        return $this->hasMany(OrdenMovilizacion::class, 'vehiculo_id');
    }

    public function parqueadero()
    {
        return $this->belongsTo(Parqueadero::class,'parqueadero_id');
    }
    public function getFotoLinkAttribute()
    {
        if(Storage::exists($this->foto)){
            return asset( Storage::url($this->foto) );
        }
        
    }
    

}
