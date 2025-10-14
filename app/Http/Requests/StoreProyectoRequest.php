<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProyectoRequest extends FormRequest
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
            'nombre' => 'required|max:255',
            'fecha_ejecucion' => 'required|date',
            'descripcion' => 'nullable',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048'
        ];
    }

    public function attributes(){
        return [
            'nombre' => 'nombre del proyecto',
            'fecha_ejecucion' => 'fecha de ejecución'
        ];
    }

    public function messages() {
        return[
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'fecha_ejecucion.required' => 'La fecha de ejecución es obligatoria.',
            'fecha_ejecucion.date' => 'La fecha de ejecución debe ser una fecha válida.'
        ];
    }
}
