<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusOrderRequest extends FormRequest
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
            'idCompra' => ['required', 'exists:compras,id'],
            'nuevoEstado' => ['required', Rule::in(['Pendiente', 'Aprobado', 'Preparando', 'Enviado', 'Finalizado'])]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nuevoEstado.in' => 'El campo nuevo estado es inválido. [Pendiente, Aprobado, Preparando, Enviado, Finalizado]'
        ];
    }
}
