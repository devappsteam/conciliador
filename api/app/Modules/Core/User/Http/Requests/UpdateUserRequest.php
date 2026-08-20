<?php

namespace App\Modules\Core\User\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Trata senha em branco enviada pelo front como "não alterar senha"
        if ($this->input('password') === '') {
            $this->merge(['password' => null, 'password_confirmation' => null]);
        }
    }

    public function rules(): array
    {
        $uuid = $this->route('uuid');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($uuid, 'uuid')],
            'password' => ['sometimes', 'nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role_uuid' => ['sometimes', 'required', 'string', 'uuid', Rule::exists('roles', 'uuid')->whereNull('deleted_at')],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
            'role_uuid' => 'perfil',
            'avatar' => 'avatar',
        ];
    }

    public function messages(): array
    {
        return [];
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
