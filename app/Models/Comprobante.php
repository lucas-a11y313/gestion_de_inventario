<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    public function compras(){
        return $this->hasMany(Compra::class);
    }

    public function ventas(){
        return $this->hasMany(Venta::class);
    }
}
