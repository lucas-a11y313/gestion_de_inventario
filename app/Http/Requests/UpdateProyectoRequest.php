<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProyectoRequest extends FormRequest
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
            'descripcion' => 'nullable',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048'
        ];
    }

    public function attributes(){
        return [
            'nombre' => 'nombre del proyecto'
        ];
    }

    public function messages() {
        return[
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.'
        ];
    }
}
