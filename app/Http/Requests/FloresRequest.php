<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FloresRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'detalles' => 'nullable',
            'precioFinal' => 'required',
            'descuento' => 'numeric',
            'precioInicial' => 'numeric',
            'stock' => 'required',
            'imagenes' => ['required'],
            'imagenes.*.urlImagen' => ['string'],
            'categorias' => ['required'],
            'categorias.*.idCategoria' => ['numeric']
        ];
    }
}
