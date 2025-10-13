<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adquisicion extends Model
{

    protected $table = 'adquisiciones';

    protected $fillable = ['fecha_hora','total','proveedore_id','tipo_adquisicion'];

    public function proveedore(){/*Relacion N a 1 */
        return $this->belongsTo(Proveedore::class);
    }

    public function productos(){/* Relacion N a N  */
        return $this->belongsToMany(Producto::class, 'adquisicion_producto')->withTimestamps()->withPivot('cantidad','precio_compra');
    }

}
