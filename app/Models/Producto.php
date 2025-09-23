<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Producto extends Model
{
    public function compras(){
        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');
    }

    public function ventas(){
        return $this->belongsToMany(Venta::class)->withTimestamps()->withPivot('cantidad','precio_venta','descuento');
    }

    public function categorias(){
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }

    public function marca(){
        return $this->belongsTo(Marca::class);
    }

    protected $fillable = ['codigo','nombre','descripcion','fecha_vencimiento','marca_id','img_path'];

    public function hanbleUploadImage($image){//Esta función va a gestionar todo lo que tenga ver con el guardado de la imagen
        
        $file = $image;

        //En la base de datos se va a guardar el nombre de la imagen por lo cual cada nombre de una imagen debe ser única, para eso le damos un nombre en función del tiempo para que sea unica
        $name = time() . $file->getClientOriginalName();//le asigno un nombre a la imagen
        
        //$file->move(public_path().'/img/productos/',$name);//el archivo lo guardo en la carpeta img\productos tambien con el nombre de la imagen
        
        Storage::putFileAs('/public/productos/',$file,$name,'public');

        return $name;//retornar el nombre ya que eso lo voy a guardar en mi base de datos
    }
}
