<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    public function ordenMovilizacion()
    {
        return $this->belongsTo(OrdenMovilizacion::class,'orden_movilizacion_id');
    }
}
