<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{

    protected $table = 'solicitudes';

    protected $fillable = ['fecha_hora','user_id','tipo_solicitud','razon'];

    public function user(){/*Relacion N a 1 */
        return $this->belongsTo(User::class);
    }

    public function productos(){/* Relacion N a N  */
        return $this->belongsToMany(Producto::class)->withTimestamps()->withPivot('cantidad','precio_compra');
    }

}
