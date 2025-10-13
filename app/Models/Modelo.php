<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    public function productos(){
        return $this->hasMany(Producto::class);
    }

    public function caracteristica(){
        return $this->belongsTo(Caracteristica::class);
    }

    protected $fillable = ['caracteristica_id'];
}
