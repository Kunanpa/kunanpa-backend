<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopingRequest extends FormRequest
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
            'idCliente' => ['required', 'numeric', 'exists:personas,id'],
            'nombres' => 'required',
            'numTelefono' => ['required', 'numeric'],
            'direccion' => 'required',
            'distrito' => 'required',
            'codigoPostal' => ['required', 'numeric'],
            'pais' => 'required',
            'nota' => ['exclude_if:nota,null', 'string'],
            'arreglos' => ['required', 'array'],
            'arreglos.*.idFlor' => ['required', 'numeric', 'exists:flores,id'],
            'arreglos.*.cantidad' => ['required', 'numeric'],
            'arreglos.*.costo' => ['required', 'numeric'],
            'total' => ['required', 'numeric']
        ];
    }
}
