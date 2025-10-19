<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public function documento(){
        return $this->belongsTo(Documento::class);/*Define una relación "pertenece a" (belongsTo) entre Persona y Documento. */
    }

    public function proveedore(){
        return $this->hasOne(Proveedore::class);
    }

    protected $fillable = ['razon_social','direccion','tipo_persona','documento_id','numero_documento'];
}
