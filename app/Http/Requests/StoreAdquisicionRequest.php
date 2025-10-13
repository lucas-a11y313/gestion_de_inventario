<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdquisicionRequest extends FormRequest
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
            'fecha_hora' => 'required',
            'total' => 'required',
            'tipo_adquisicion' => 'nullable|max:255'
        ];
    }

    public function attributes(){
        return [
            'proveedore_id' => 'proveedor',
            'tipo_adquisicion' => 'tipo de adquisición'
        ];
    }

    public function messages() {
        return[
            'proveedore_id.required' => 'El campo proveedor es obligatorio rellenar.'
        ];
    }
}
