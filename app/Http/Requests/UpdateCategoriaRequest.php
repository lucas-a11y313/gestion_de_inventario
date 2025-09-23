<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaRequest extends FormRequest
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
        $categoria = $this->route('categoria');//hace que recupere los datos que estan en la variable 'categoria' que está en la ruta llamada 'categorias.update'
        $caracteristicaId = $categoria->caracteristica->id;
        return [
            'nombre' => 'required|max:60|unique:caracteristicas,nombre,'.$caracteristicaId,//Verifica que el campo nombre sea único en la tabla caracteristicas, excepto para el registro que tiene el ID igual a $caracteristicaId.
            'descripcion' => 'nullable|max:255'
        ];
    }
}
