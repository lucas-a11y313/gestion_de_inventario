<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    protected $fillable = ['nombre','fecha_ejecucion','descripcion','imagen','estado'];

    public function productos(){
        return $this->belongsToMany(Producto::class, 'proyecto_producto')->withTimestamps()->withPivot('cantidad');
    }
}
