<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    public function compras(){
        return $this->hasMany(Compra::class);
    }

    protected $fillable = ['persona_id'];
}
