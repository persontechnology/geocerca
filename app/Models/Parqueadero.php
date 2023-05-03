<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Parqueadero extends Model
{
    
    use HasSpatial;

    #region relaciones

    protected $fillable = [
        'nombre',
        'descripcion',
        'area',
    ];
    protected $casts = [
        'area' => Polygon::class,
    ];
    

    public function guardias()
    {
        return $this->belongsToMany(User::class, 'guardia_parqueaderos', 'parqueadero_id', 'guardia_id')->wherePivot('estado', 'Activo');
    }
    

    // Deivid, veirifcar si un parqueadero tiene un guardia
    public function hasGuardia($idUser)
    {
        return $this->guardias()->where('guardia_id',$idUser)->first();
            
    }
}
