<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
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
            'document_type' => 'required',
            'document_number' => 'required|unique:students,document_number,' . $this->id . ',id,agency_id,' . $this->agency_id,
            'name' => 'required',
            'paternal_surname' => 'required_without:maternal_surname',
            'maternal_surname' => 'required_without:paternal_surname',
            'email' => 'required|email|unique:students,email,' . $this->id . ',id,agency_id,' . $this->agency_id,
            'phone_number' => 'required',
            'link' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'document_type.required' => 'El tipo de documento es requerido',
            'document_number.required' => 'El número de documento es requerido',
            'document_number.unique' => 'El número de documento ya existe en la sub agencia',
            'name.required' => 'El nombre es requerido',
            'paternal_surname.required_without' => 'El apellido paterno es requerido',
            'maternal_surname.required_without' => 'El apellido materno es requerido',
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo debe ser un correo válido',
            'email.unique' => 'El correo ya existe',
            'phone_number.required' => 'El teléfono es requerido',
            'phone_number.digits' => 'El teléfono debe tener 9 dígitos',
            'link.url' => 'El enlace debe ser una URL válida',
        ];
    }
}
