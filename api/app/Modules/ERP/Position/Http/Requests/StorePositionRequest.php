<?php

namespace App\Modules\ERP\Position\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'code'          => ['required', 'string', 'max:50', 'unique:positions,code'],
            'description'   => ['nullable', 'string'],
            'is_active'     => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'          => 'Nome',
            'code'          => 'Código',
            'description'   => 'Descrição',
            'is_active'     => 'Ativo',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'O campo :attribute é obrigatório.',
            'name.string'           => 'O campo :attribute deve ser uma string.',
            'name.max'              => 'O campo :attribute não pode ter mais de :max caracteres.',
            'code.required'         => 'O campo :attribute é obrigatório.',
            'code.string'           => 'O campo :attribute deve ser uma string.',
            'code.max'              => 'O campo :attribute não pode ter mais de :max caracteres.',
            'code.unique'           => 'O campo :attribute deve ser único.',
            'description.string'    => 'O campo :attribute deve ser uma string.',
            'is_active.boolean'     => 'O campo :attribute deve ser verdadeiro ou falso.',
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
