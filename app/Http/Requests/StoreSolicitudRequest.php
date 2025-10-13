<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'fecha_hora' => 'required',
            'tipo_solicitud' => 'required|in:retiro,prestamo',
            'razon' => 'required|string|max:500'
        ];
    }

    public function attributes(){
        return [
            'user_id' => 'usuario',
            'tipo_solicitud' => 'tipo de solicitud',
            'razon' => 'razón'
        ];
    }

    public function messages() {
        return[
            'user_id.required' => 'El campo usuario es obligatorio rellenar.',
            'tipo_solicitud.required' => 'El campo tipo de solicitud es obligatorio rellenar.',
            'razon.required' => 'El campo razón es obligatorio rellenar.'
        ];
    }
}
