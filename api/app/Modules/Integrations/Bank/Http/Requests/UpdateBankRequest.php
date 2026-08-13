<?php

namespace App\Modules\Integrations\Bank\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('banks', 'code')->ignore($this->route('bank'), 'uuid')],
            'logo' => ['nullable', 'image', 'max:1024', 'mimes:jpeg,png,jpg,svg,webp'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nome do banco',
            'code' => 'Código do banco',
            'logo' => 'Logo do banco',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'name.string' => 'O campo :attribute deve ser uma string.',
            'name.max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'code.required' => 'O campo :attribute é obrigatório.',
            'code.string' => 'O campo :attribute deve ser uma string.',
            'code.max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'code.unique' => 'O código do banco já está em uso.',
            'logo.image' => 'O campo :attribute deve ser uma imagem.',
            'logo.max' => 'O campo :attribute não pode ter mais de :max kilobytes.',
            'logo.mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
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
