<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    protected $fillable=[
        'nombre',
        'departamento_id'
    ];

    public function direccion()
    {
        return $this->belongsTo(Direccion::class,'departamento_id');
    }

}
