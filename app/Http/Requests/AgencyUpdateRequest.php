<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgencyUpdateRequest extends FormRequest
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
            'code_nsc' => ['required', 'unique:agencies,code_nsc,' . $this->id],
            //required, string, unique:agencies,name
            'name' => ['required', 'unique:agencies,name,' . $this->id],
            //required, string, unique:agencies,ruc length:11 only numbers
            'ruc' => ['required', 'unique:agencies,ruc,' . $this->id],
            'denomination' => ['required'],
            //required, email, unique:agencies,email_institutional
            'email_institutional' => ['required', 'email', 'unique:agencies,email_institutional,' . $this->id],
            //required, string unique:agencies,phone
            'phone' => ['required', 'unique:agencies,phone,' . $this->id],
            //required, date, after:today 
            'license_start' => ['required', 'date'],
            //required, date, after:license_start 
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
            'ruc.unique' => 'El RUC ya existe',
            'denomination.required' => 'La denominación es requerida',
            'email_institutional.required' => 'El correo institucional es requerido',
            'email_institutional.email' => 'El correo institucional no es válido',
            'email_institutional.unique' => 'El correo institucional ya existe',
            'phone.required' => 'El teléfono es requerido',
            'phone.unique' => 'El teléfono ya existe',
            'license_start.required' => 'La fecha de inicio de licencia es requerida',
            'license_start.date' => 'La fecha de inicio de licencia no es válida',  
            'license_end.required' => 'La fecha de fin de licencia es requerida',
            'license_end.date' => 'La fecha de fin de licencia no es válida',
            'license_end.after' => 'La fecha de fin de licencia debe ser mayor a la fecha de inicio de licencia',
        ];
    }
}
