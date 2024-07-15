<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgencyStoreRequest extends FormRequest
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
            'code_nsc' => ['required', 'unique:agencies,code_nsc'],
            'name' => ['required', 'unique:agencies,name'],
            'ruc' => ['required', 'digits:11', 'unique:agencies,ruc'],
            'denomination' => ['required', 'max:255'],
            'email_institutional' => ['required', 'email', 'unique:agencies,email_institutional'],
            'phone' => ['required', 'unique:agencies,phone', 'digits:9'],
            'license_start' => ['required', 'date'],
            'license_end' => ['required', 'date', 'after:license_start'],

        ];
    }

    public function messages(): array
    {
        return [
            'code_nsc.required' => 'El código NSC es requerido',
            'code_nsc.unique' => 'El código NSC ya existe',
            'name.required' => 'El nombre es requerido',
            'name.unique' => 'El nombre ya existe',
            'ruc.required' => 'El RUC es requerido',
            'ruc.digits' => 'El RUC debe tener 11 dígitos',
            'ruc.unique' => 'El RUC ya existe',
            'denomination.required' => 'La denominación es requerida',
            'denomination.max' => 'La denominación no debe exceder los 255 caracteres',
            'email_institutional.required' => 'El correo institucional es requerido',
            'email_institutional.email' => 'El correo institucional debe ser un correo válido',
            'email_institutional.unique' => 'El correo institucional ya existe',
            'phone.required' => 'El teléfono es requerido',
            'phone.digits' => 'El teléfono debe tener 9 dígitos',
            'phone.unique' => 'El teléfono ya existe',
            'license_start.required' => 'La fecha de inicio de licencia es requerida',
            'license_start.date' => 'La fecha de inicio de licencia debe ser una fecha válida',
            'license_end.required' => 'La fecha de fin de licencia es requerida',
            'license_end.date' => 'La fecha de fin de licencia debe ser una fecha válida',
            'license_end.after' => 'La fecha de fin de licencia debe ser mayor a la fecha de inicio de licencia',
        ];
    }
}
