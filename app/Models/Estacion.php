<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
class Estacion extends Model
{
    use HasSpatial;

    protected $casts = [
        'area' => Polygon::class,
    ];
    
    
}
