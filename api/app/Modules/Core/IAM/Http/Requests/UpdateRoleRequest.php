<?php

namespace App\Modules\Core\IAM\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $role = $this->route('role');
        $roleId = is_object($role) ? $role->uuid : $role;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($roleId, 'uuid')],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions'   => ['sometimes', 'array'],
            'permissions.*' => ['uuid', 'exists:permissions,uuid'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nome',
            'description' => 'Descrição',
            'permissions' => 'Permissões',
            'permissions.*' => 'Permissão',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'name.string' => 'O campo :attribute deve ser uma string.',
            'name.max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'name.unique' => 'O campo :attribute já está em uso.',
            'description.string' => 'O campo :attribute deve ser uma string.',
            'description.max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'permissions.array' => 'O campo :attribute deve ser um array.',
            'permissions.*.uuid' => 'O campo :attribute deve ser um UUID válido.',
            'permissions.*.exists' => 'O campo :attribute deve existir na tabela de permissões.',
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
