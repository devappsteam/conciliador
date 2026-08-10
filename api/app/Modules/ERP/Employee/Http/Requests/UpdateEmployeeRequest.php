<?php

namespace App\Modules\ERP\Employee\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');
        return [
            'department'        => ['required', 'exists:departments,uuid'],
            'position'          => ['required', 'exists:positions,uuid'],
            'employee_code'     => ['required', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($employee, 'uuid')],
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'preferred_name'    => ['nullable', 'string', 'max:255'],
            'birth_date'        => ['nullable', 'date'],
            'gender'            => ['nullable', 'string', 'max:50'],
            'cpf'               => ['required', 'string', 'max:25', Rule::unique('employees', 'cpf')->ignore($employee, 'uuid')],
            'rg'                => ['nullable', 'string', 'max:25'],
            'rg_issuer'         => ['nullable', 'string', 'max:50'],
            'pis_pasep'         => ['nullable', 'string', 'max:50'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($employee, 'uuid')],
            'personal_email'    => ['nullable', 'string', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:40'],
            'mobile_phone'      => ['nullable', 'string', 'max:40'],
            'zip_code'          => ['nullable', 'string', 'max:15'],
            'street'            => ['nullable', 'string', 'max:255'],
            'number'            => ['nullable', 'string', 'max:50'],
            'complement'        => ['nullable', 'string', 'max:255'],
            'neighborhood'      => ['nullable', 'string', 'max:255'],
            'city'              => ['nullable', 'string', 'max:100'],
            'state'             => ['nullable', 'string', 'max:2'],
            'country'           => ['nullable', 'string', 'max:2'],
            'hire_date'         => ['nullable', 'date'],
            'termination_date'  => ['nullable', 'date'],
            'status'            => ['nullable', 'in:active,inactive,terminated'],
            'notes'             => ['nullable', 'string'],
            'profile_picture'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'department'        => 'Departamento',
            'position'          => 'Cargo',
            'employee_code'     => 'Código do funcionário',
            'first_name'        => 'Nome',
            'last_name'         => 'Sobrenome',
            'preferred_name'    => 'Nome preferido',
            'birth_date'        => 'Data de nascimento',
            'gender'            => 'Gênero',
            'cpf'               => 'CPF',
            'rg'                => 'RG',
            'rg_issuer'         => 'Órgão emissor do RG',
            'pis_pasep'         => 'PIS/PASEP',
            'email'             => 'E-mail',
            'personal_email'    => 'E-mail pessoal',
            'phone'             => 'Telefone',
            'mobile_phone'      => 'Celular',
            'zip_code'          => 'CEP',
            'street'            => 'Rua',
            'number'            => 'Número',
            'complement'        => 'Complemento',
            'neighborhood'      => 'Bairro',
            'city'              => 'Cidade',
            'state'             => 'Estado',
            'country'           => 'País',
            'hire_date'         => 'Data de contratação',
            'termination_date'  => 'Data de demissão',
            'status'            => 'Status',
            'notes'             => 'Observações',
            'profile_picture'   => 'Foto de perfil',
        ];
    }

    public function messages(): array
    {
        return [
            'department.required'       => 'O campo :attribute é obrigatório.',
            'position.required'         => 'O campo :attribute é obrigatório.',
            'employee_code.required'    => 'O campo :attribute é obrigatório.',
            'first_name.required'       => 'O campo :attribute é obrigatório.',
            'last_name.required'        => 'O campo :attribute é obrigatório.',
            'cpf.required'              => 'O campo :attribute é obrigatório.',
            'email.required'            => 'O campo :attribute é obrigatório.',
            'personal_email.required'   => 'O campo :attribute é obrigatório.',
            'profile_picture.image'      => 'O campo :attribute deve ser uma imagem.',
            'profile_picture.mimes'     => 'O campo :attribute deve ser um arquivo do tipo: :values.',
            'profile_picture.max'       => 'O campo :attribute não deve ser maior que 2MB.',

        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if (!$this->expectsJson()) {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(
            response()->json([
                'message' => 'Falha na validação dos dados fornecidos.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
