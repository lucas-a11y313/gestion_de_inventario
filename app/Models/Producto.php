<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Producto extends Model
{
    public function adquisiciones(){
        return $this->belongsToMany(Adquisicion::class, 'adquisicion_producto')->withTimestamps()->withPivot('cantidad','precio_compra');
    }

    public function categorias(){
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    public function inventarioBPs(){
        return $this->hasMany(InventarioBP::class);
    }

    public function solicitudes(){
        return $this->belongsToMany(Solicitud::class)->withTimestamps()->withPivot('cantidad','precio_compra', 'fecha_devolucion');
    }

    public function proyectos(){
        return $this->belongsToMany(Proyecto::class, 'proyecto_producto')->withTimestamps()->withPivot('cantidad');
    }

    protected $fillable = ['codigo','nombre','descripcion','fecha_vencimiento','marca_id','img_path','tipo','ubicacion','sugerencia'];

    public function hanbleUploadImage($image){//Esta función va a gestionar todo lo que tenga ver con el guardado de la imagen
        
        $file = $image;

        //En la base de datos se va a guardar el nombre de la imagen por lo cual cada nombre de una imagen debe ser única, para eso le damos un nombre en función del tiempo para que sea unica
        $name = time() . $file->getClientOriginalName();//le asigno un nombre a la imagen
        
        //$file->move(public_path().'/img/productos/',$name);//el archivo lo guardo en la carpeta img\productos tambien con el nombre de la imagen
        
        Storage::putFileAs('productos',$file,$name,'public');

        return $name;//retornar el nombre ya que eso lo voy a guardar en mi base de datos
    }
}
