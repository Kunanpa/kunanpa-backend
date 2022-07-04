<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            // Reglas de validacion
            'email' => ['required', 'email'],
            'password' => 'required',
            'nombre' => 'required',
            'avatar' => ['exclude_if:avatar,null', 'image'],
            'dni' => ['exclude_if:dni,null', 'integer', 'digits:8'],
            'direccion' => ''
        ];
    }
}
