<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdministratorRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        return [
            'document_number' => 'required|min:8|unique:administrators,document_number,' . $this->id,
            'name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->id . ',profile_id',
            'role' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'document_number.required' => 'El número de documento es requerido',
            'document_number.unique' => 'El número de documento ya existe',
            'document_number.min' => 'El número de documento debe tener al menos 8 caracteres',
            'name.required' => 'El nombre es requerido',
            'last_name.required' => 'El apellido es requerido',
            'phone_number.required' => 'El teléfono es requerido',
            'username.required' => 'El nombre de usuario es requerido',
            'username.unique' => 'El nombre de usuario ya existe',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo no es válido',
            'email.unique' => 'El correo ya existe',
            'role.required' => 'El rol es requerido',
        ];
    }
}
