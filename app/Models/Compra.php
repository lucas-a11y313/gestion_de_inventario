<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{

    protected $fillable = ['fecha_hora','numero_comprobante','total','comprobante_id','proveedore_id'];
    
    public function proveedore(){/*Relacion N a 1 */
        return $this->belongsTo(Proveedore::class);
    }

    public function comprobante(){/*Relacion N a 1 */
        return $this->belongsTo(Comprobante::class);
    }

    public function productos(){/* Relacion N a N  */
        return $this->belongsToMany(Producto::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');
    }

}
