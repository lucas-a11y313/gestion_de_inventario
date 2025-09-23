<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comprobante(){
        return $this->belongsTo(Comprobante::class);
    }

    public function productos(){
        return $this->belongsToMany(Producto::class)->withTimestamps()->withPivot('cantidad','precio_venta','descuento');
    }

    protected $fillable = ['fecha_hora','numero_comprobante','total','cliente_id','user_id','comprobante_id'];
}
