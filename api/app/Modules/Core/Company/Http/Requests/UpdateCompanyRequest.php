<?php

namespace App\Modules\Core\Company\Http\Requests;

use App\Modules\Core\Company\Enums\Status;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'corporate_name'            => ['sometimes', 'string', 'max:255'],
            'trade_name'                => ['sometimes', 'string', 'max:255'],
            'state_registration'        => ['sometimes', 'string', 'max:30'],
            'municipal_registration'    => ['sometimes', 'string', 'max:30'],
            'email'                     => ['sometimes', 'string', 'email', 'max:255'],
            'phone'                     => ['sometimes', 'string', 'max:30'],
            'street'                    => ['sometimes', 'string', 'max:255'],
            'number'                    => ['sometimes', 'string', 'max:20'],
            'complement'                => ['sometimes', 'string', 'max:255'],
            'neighborhood'              => ['sometimes', 'string', 'max:255'],
            'city'                      => ['sometimes', 'string', 'max:255'],
            'state'                     => ['sometimes', 'string', 'max:2'],
            'zip_code'                  => ['sometimes', 'string', 'max:15'],
            'country'                   => ['sometimes', 'string', 'max:2'],
            'status'                    => ['sometimes', 'string', Rule::enum(Status::values())],
        ];
    }

    public function attributes(): array
    {
        return [
            'corporate_name'            => 'Razão Social',
            'trade_name'                => 'Nome Fantasia',
            'state_registration'        => 'Inscrição Estadual',
            'municipal_registration'    => 'Inscrição Municipal',
            'email'                     => 'E-mail',
            'phone'                     => 'Telefone',
            'street'                    => 'Logradouro',
            'number'                    => 'Número',
            'complement'                => 'Complemento',
            'neighborhood'              => 'Bairro',
            'city'                      => 'Cidade',
            'state'                     => 'Estado',
            'zip_code'                  => 'CEP',
            'country'                   => 'País',
            'status'                    => 'Status',
        ];
    }

    public function messages(): array
    {
        return [
            'corporate_name.required'       => 'O campo Razão Social é obrigatório.',
            'corporate_name.string'         => 'O campo Razão Social deve ser uma string.',
            'corporate_name.max'            => 'O campo Razão Social não pode ter mais de 255 caracteres.',
            'trade_name.string'             => 'O campo Nome Fantasia deve ser uma string.',
            'trade_name.max'                => 'O campo Nome Fantasia não pode ter mais de 255 caracteres.',
            'state_registration.string'     => 'O campo Inscrição Estadual deve ser uma string.',
            'state_registration.max'        => 'O campo Inscrição Estadual não pode ter mais de 30 caracteres.',
            'municipal_registration.string' => 'O campo Inscrição Municipal deve ser uma string.',
            'municipal_registration.max'    => 'O campo Inscrição Municipal não pode ter mais de 30 caracteres.',
            'email.string'                  => 'O campo E-mail deve ser uma string.',
            'email.email'                   => 'O campo E-mail deve ser um endereço de e-mail válido.',
            'email.max'                     => 'O campo E-mail não pode ter mais de 255 caracteres.',
            'phone.string'                  => 'O campo Telefone deve ser uma string.',
            'phone.max'                     => 'O campo Telefone não pode ter mais de 30 caracteres.',
            'street.string'                 => 'O campo Logradouro deve ser uma string.',
            'street.max'                    => 'O campo Logradouro não pode ter mais de 255 caracteres.',
            'number.string'                 => 'O campo Número deve ser uma string.',
            'number.max'                    => 'O campo Número não pode ter mais de 20 caracteres.',
            'complement.string'             => 'O campo Complemento deve ser uma string.',
            'complement.max'                => 'O campo Complemento não pode ter mais de 255 caracteres.',
            'neighborhood.string'           => 'O campo Bairro deve ser uma string.',
            'neighborhood.max'              => 'O campo Bairro não pode ter mais de 255 caracteres.',
            'city.string'                   => 'O campo Cidade deve ser uma string.',
            'city.max'                      => 'O campo Cidade não pode ter mais de 255 caracteres.',
            'state.string'                  => 'O campo Estado deve ser uma string.',
            'state.max'                     => 'O campo Estado não pode ter mais de 2 caracteres.',
            'zip_code.string'               => 'O campo CEP deve ser uma string.',
            'zip_code.max'                  => 'O campo CEP não pode ter mais de 15 caracteres.',
            'country.string'                => 'O campo País deve ser uma string.',
            'country.max'                   => 'O campo País não pode ter mais de 2 caracteres.',
            'status.string'                 => 'O campo Status deve ser uma string.',
            'status.in'                     => 'O campo Status deve ser um dos seguintes valores: ' . implode(', ', Status::values()) . '.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if (!$this->expectsJson()) {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
