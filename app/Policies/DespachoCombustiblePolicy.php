<?php

namespace App\Policies;

use App\Models\DespachoCombustible;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DespachoCombustiblePolicy
{
    use HandlesAuthorization;

   
    public function update(User $user, DespachoCombustible $despachoCombustible)
    {
        if($despachoCombustible->estado!='Despachado'){
            return true;
        }
        return false;
    }

//    DEivid, acceder solo ami despacho para ingresar dc
    public function ingresarCombustible(User $user, DespachoCombustible $dc)
    {
        if($user->id==$dc->conductor->id){
            return true;
        }
        return false;
    }

    // para guardar el ingreso de despacho de combustible solo puede hacer el condutor
    public function guardarIngresoCombustible(User $user, DespachoCombustible $dc)
    {
        if($user->id==$dc->conductor->id && $dc->estado==='Autorizado'){
            return true;
        }
        return false;
    }
}
