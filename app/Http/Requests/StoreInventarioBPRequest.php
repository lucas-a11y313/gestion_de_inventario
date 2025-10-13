<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarioBPRequest extends FormRequest
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
            'bp' => 'required|string|max:50|unique:inventariobps,bp',
            'producto_id' => 'required|integer|exists:productos,id',
            'user_id' => 'required|integer|exists:users,id',
            'origen' => 'nullable|string|max:100'
        ];
    }

    public function attributes()
    {
        return [
            'producto_id' => 'producto',
            'user_id' => 'responsable'
        ];
    }

    public function messages()
    {
        return [
            'bp.required' => 'El campo BP es obligatorio.',
            'bp.unique' => 'Este código BP ya existe en el sistema.',
            'producto_id.required' => 'Debe seleccionar un producto.',
            'user_id.required' => 'Debe seleccionar un responsable.'
        ];
    }
}
