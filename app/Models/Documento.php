<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    //
    public function personas(){
        return $this->hasMany(Persona::class);/*Define una relación "uno a muchos" (hasMany) entre el modelo Documento y el modelo Persona. Persona::class: Especifica que la relación está con el modelo Persona.
        */
    }
}
