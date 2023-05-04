<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Deivid, apellidos y nombres de usuario
    public function getApellidosNombresAttribute($from)
    {
        if($this->apellidos && $this->nombres){
            return $this->apellidos.' '.$this->nombres;
        }else{
            return $this->name;
        }
    }

    // deivid, obtener foto de perfil
    public function getFirmaLinkAttribute()
    {
        if(Storage::exists($this->firma)){
            return Storage::url($this->firma) ;
        }
        
    }

    // DEivid creo que ya no funciona cuando programe para el ingreso de kilometrajes en la consulat de usuario esta en varios parqueaderos
    // public function parqueadero()
    // {
    //     return $this->belongsTo(Parqueadero::class);
    // }

    // Un usuario tiene una sola configuracion
    public function configuracion()
    {
        return $this->hasOne(Configuracion::class);
    }


    // Deivid: un usuario tiene notiifcaciones
    public function notificacionesLecturas()
    {
        return $this->hasMany(NotificacionLectura::class,'guardia_id');
    }

    // estaciones
    public function estacionServicios()
    {
        return $this->belongsToMany(Estacion::class, 'despachadors', 'despachador_id', 'estacion_id');
    }

    // DEivid, un guardia esta en varias parqueaderos
    public function parqueaderos()
    {
        return $this->belongsToMany(Parqueadero::class, 'guardia_parqueaderos', 'guardia_id', 'parqueadero_id');
    }
    
    // Deivid, un chofer tiene asignado varios despacho de combustible
    public function despachoCombustibles()
    {
        return $this->hasMany(DespachoCombustible::class,'chofer_id');
    }

}
