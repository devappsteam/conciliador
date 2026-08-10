<?php

namespace App\Modules\ERP\Department\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['nullable', 'string', 'max:50', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Nome',
            'code'        => 'Código',
            'description' => 'Descrição',
            'is_active'   => 'Ativo',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'name.string'   => 'O campo :attribute deve ser uma string.',
            'name.max'      => 'O campo :attribute não pode ter mais de :max caracteres.',

            'code.string'   => 'O campo :attribute deve ser uma string.',
            'code.max'      => 'O campo :attribute não pode ter mais de :max caracteres.',
            'code.unique'   => 'O valor do campo :attribute já está em uso.',

            'description.string' => 'O campo :attribute deve ser uma string.',

            'is_active.boolean'  => 'O campo :attribute deve ser verdadeiro ou falso.',
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
