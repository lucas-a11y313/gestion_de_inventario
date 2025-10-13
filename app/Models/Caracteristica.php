<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    public function categoria(){
        return $this->hasOne(Categoria::class);
    }

    public function marca(){
        return $this->hasOne(Marca::class);
    }

    public function modelo(){
        return $this->hasOne(Modelo::class);
    }

    protected $fillable = ['nombre','descripcion'];
}
