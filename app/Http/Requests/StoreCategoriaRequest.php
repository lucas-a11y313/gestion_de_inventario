<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends FormRequest
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
    {//rules es un método que devuelve un array con las reglas de validación que serán aplicadas.
        return [
            'nombre' => 'required|max:60|unique:caracteristicas,nombre',/*required: El campo es obligatorio.Unique: hace que nombre sea unico en la tabla caracteristicas,basicamente los valores de nombre no pueden repetirse  */
            'descripcion' => 'nullable|max:255'
        ];
    }
}
