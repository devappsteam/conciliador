<?php

namespace App\Modules\Integrations\Brand\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('brands', 'code')->ignore($this->route('uuid'), 'uuid')],
            'logo' => ['nullable', 'image', 'max:1024', 'mimes:jpeg,png,jpg,svg,webp'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nome da bandeira',
            'code' => 'Código da bandeira',
            'logo' => 'Logo da bandeira',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'code.required' => 'O campo :attribute é obrigatório.',
            'code.unique'   => 'O código da bandeira já está em uso.',
            'logo.image'    => 'O campo :attribute deve ser uma imagem.',
            'logo.max'      => 'O campo :attribute não pode ter mais de :max kilobytes.',
            'logo.mimes'    => 'O campo :attribute deve ser um arquivo do tipo: :values.',
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
