<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProveedorRequest extends FormRequest
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
        $proveedore = $this->route('proveedore');
        return [
            'razon_social' => 'required|max:80',
            'direccion' => 'required|max:80',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|string|unique:personas,numero_documento,'.$proveedore->persona->id
        ];
    }

    public function attributes(){
        return[
            'razon_social' =>'razón social',
            'direccion' => 'dirección',
            'numero_documento' => 'número de documento'
        ];
    }

    public function messages(){
        //esta funcion te permite personalizar los mensajes de error
        return[
            'razon_social.required' => 'El campo nombre es obligatorio rellenar.',
            'direccion.required' => 'El campo dirección es obligatorio rellenar.',
            'documento_id.required' => 'El campo tipo de documento es obligatorio rellenar.',
            'numero_documento.required' => 'El campo número de documento es obligatorio rellenar.'
        ];
    }
}
