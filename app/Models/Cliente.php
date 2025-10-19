<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function persona(){
        return $this->belongsTo(Persona::class);
    }

    protected $fillable = ['persona_id'];
}
