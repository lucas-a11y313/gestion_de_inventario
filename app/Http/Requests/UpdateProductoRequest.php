<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto = $this->route('producto');
        return [
            'codigo' => 'required|max:50|unique:productos,codigo,'.$producto->id,
            'nombre' => 'required|max:80|unique:productos,nombre,'.$producto->id,
            'descripcion' => 'nullable|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'img_path' => 'nullable|max:2048|image|mimes:png,jpg,jpeg',
            'tipo' => 'required|in:BP,Insumo',
            'marca_id' => 'required|integer|exists:marcas,id',
            'categorias' => 'required',
            'ubicacion' => 'nullable|string|max:100',
            'origen' => 'nullable|string|max:100',
            'sugerencia' => 'nullable|string'
        ];
    }

    public function attributes(){
        //Esta funcion te permite cambiar los nombre de los campos en el mensaje de error, si no queres que apareza como "marca id" podes optar poner como "marca"
        return[
            'marca_id' => 'marca'
        ];

    }

    public function messages(){
        //esta funcion te permite personalizar los mensajes de error, por ejemplo: queres cambiar este mensaje "*El campo codigo es obligatorio." por este mensaje "Se necesita el campo código" 
        return[
            'codigo.required' => 'Se necesita un campo código.',
            'nombre.required' => 'Se necesita un campo nombre.'
        ];
    }
}
