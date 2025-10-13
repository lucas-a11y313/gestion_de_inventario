<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    protected $fillable = ['nombre','descripcion','fecha_inicio','fecha_fin','imagen','estado'];

    public function productos(){
        return $this->belongsToMany(Producto::class, 'proyecto_producto')->withTimestamps()->withPivot('cantidad');
    }
}
