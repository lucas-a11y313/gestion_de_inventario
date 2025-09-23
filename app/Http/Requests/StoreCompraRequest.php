<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
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
        return [
            'proveedore_id' => 'required|exists:proveedores,id',
            'comprobante_id' => 'required|exists:comprobantes,id',
            'numero_comprobante' => 'required|unique:compras,numero_comprobante|max:255',
            'fecha_hora' => 'required',
            'total' => 'required'
        ];
    }

    public function attributes(){
        //Esta funcion te permite cambiar los nombre de los campos en el mensaje de error, si no queres que apareza como "proveedore_id" podes optar poner como "proveedor"
        return [
            'proveedore_id' => 'proveedor',
            'comprobante_id' => 'comprobante',
            'numero_comprobante' => 'número de comprobante'
        ];
    }

    public function messages() {
        //esta funcion te permite personalizar los mensajes de error, por ejemplo: queres cambiar este mensaje "*El campo proveedore id es obligatorio." por este mensaje "El campo proveedor es obligatorio rellenar." 
        return[
            'proveedore_id.required' => 'El campo proveedor es obligatorio rellenar.',
            'comprobante_id.required' => 'El campo comprobante es obligatorio rellenar.',
            'numero_comprobante.required' => 'El campo número de comprobante es obligatorio rellenar.'
        ];
    }
}
