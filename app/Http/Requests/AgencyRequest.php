<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgencyRequest extends FormRequest
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
            //required, string, unique:agencies,code_nsc
            'code_nsc' => ['required', 'string', 'unique:agencies,code_nsc'],
            //required, string, unique:agencies,name
            'name' => ['required', 'string', 'unique:agencies,name'],
            //required, string, unique:agencies,ruc length:11 only numbers
            'ruc' => ['unique:agencies,ruc'],
            'denomination' => ['required'],
            //required, email, unique:agencies,email_institutional
            'email_institutional' => ['required', 'email', 'unique:agencies,email_institutional'],
            //required, string unique:agencies,phone
            'phone' => ['required', 'unique:agencies,phone'],
            //required, date, after:today 
            'license_start' => ['required', 'date'],
            //required, date, after:license_start 
            'license_end' => ['required', 'date', 'after:license_start'],

        ];
    }

    public function messages(): array
    {
        return [
            'code_nsc.required' => 'El código NSC es requerido.',
            'code_nsc.string' => 'El código NSC debe ser una cadena de texto.',
            'code_nsc.unique' => 'El código NSC ya está en uso.',
            'name.required' => 'El nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.unique' => 'El nombre ya está en uso.',
            'ruc.required' => 'El RUC es requerido.',
            'ruc.digits' => 'El RUC debe tener 11 dígitos.',
            'ruc.unique' => 'El RUC ya está en uso.',
            'denomination.required' => 'La denominación es requerida.',
            'email_institutional.required' => 'El correo electrónico institucional es requerido.',
            'email_institutional.email' => 'El correo electrónico institucional no es válido.',
            'email_institutional.unique' => 'El correo electrónico institucional ya está en uso.',
            'phone.required' => 'El teléfono es requerido.',
            'phone.unique' => 'El teléfono ya está en uso.',
            'license_start.required' => 'La fecha de inicio de licencia es requerida.',
            'license_start.date' => 'La fecha de inicio de licencia no es válida.',
            'license_end.required' => 'La fecha de fin de licencia es requerida.',
            'license_end.date' => 'La fecha de fin de licencia no es válida.',
            'license_end.after' => 'La fecha de fin de licencia debe ser después de la fecha de inicio de licencia.',
        ];
    }
}
