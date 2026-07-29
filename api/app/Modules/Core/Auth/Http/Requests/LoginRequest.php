<?php

namespace App\Modules\Core\Auth\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email'    => 'E-mail',
            'password' => 'Senha',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'O campo :attribute é obrigatório.',
            'email.string'      => 'O campo :attribute deve ser uma string.',
            'email.email'       => 'O campo :attribute deve ser um e-mail válido.',
            'email.max'         => 'O campo :attribute não pode ter mais de :max caracteres.',
            'password.required' => 'O campo :attribute é obrigatório.',
            'password.string'   => 'O campo :attribute deve ser uma string.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if (!$this->expectsJson()) {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(
            response()->json([
                'message' => 'Falha na validação, verifique os campos e tente novamente.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
