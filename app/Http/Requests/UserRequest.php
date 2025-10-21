<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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

        $method = $this->method();
        $id = $this->route('usuario') ?? Auth::id(); //solo agregar cuando se este editando el perfil
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ];

        if ($method === 'POST') {
            $rules['password'] = 'required|min:8|confirmed'; // Requerido solo en POST (crear)
        } else if (in_array($method, ['PUT', 'PATCH'])) {
            $rules['password'] = 'nullable|min:8|confirmed'; // No obligatorio en PUT (editar)
        }

        return $rules;

        // return [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email,' . $this->route('usuario'),
        //     'password' => 'required|min:8|confirmed',
        // ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no puede superar los 255 caracteres.',

            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya esta registrado.',

            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
