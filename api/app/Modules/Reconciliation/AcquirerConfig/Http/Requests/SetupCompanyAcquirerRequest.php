<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Modules\Reconciliation\AcquirerConfig\Enums\ProductTypeEnum;
use App\Modules\Reconciliation\AcquirerConfig\Enums\AnticipationTypeEnum;
use Illuminate\Validation\Rule;

class SetupCompanyAcquirerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'acquirer' => ['required', 'uuid', 'exists:acquirers,uuid'],
            'merchant_id' => ['required', 'string', 'max:255'],
            'is_active'   => ['sometimes', 'boolean'],

            'rates'                     => ['required', 'array', 'min:1'],
            'rates.*.product_type'      => ['required', Rule::enum(ProductTypeEnum::class)],
            'rates.*.brand'             => ['required', 'string', 'max:50'],
            'rates.*.installment_min'   => ['required', 'integer', 'min:1'],
            'rates.*.installment_max'   => ['required', 'integer', 'gte:rates.*.installment_min'],
            'rates.*.rate_percentage'   => ['required', 'numeric', 'min:0'],
            'rates.*.rate_fixed'        => ['required', 'numeric', 'min:0'],
            'rates.*.effective_date'    => ['required', 'date_format:Y-m-d'],

            'anticipation'                          => ['sometimes', 'array'],
            'anticipation.anticipation_type'        => ['required_with:anticipation', Rule::enum(AnticipationTypeEnum::class)],
            'anticipation.rate_percentage_monthly'  => ['nullable', 'numeric', 'min:0'],
            'anticipation.rate_fixed_per_operation' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'acquirer.required'                             => 'O campo :attribute é obrigatório.',
            'acquirer.uuid'                                 => 'O campo :attribute deve ser um UUID válido.',
            'acquirer.exists'                               => 'O :attribute fornecido não existe.',
            'merchant_id.required'                          => 'O campo :attribute é obrigatório.',
            'merchant_id.string'                            => 'O campo :attribute deve ser uma string.',
            'merchant_id.max'                               => 'O campo :attribute não pode ter mais de :max caracteres.',
            'is_active.boolean'                             => 'O campo :attribute deve ser verdadeiro ou falso.',
            'rates.required'                                => 'O campo :attribute é obrigatório.',
            'rates.array'                                   => 'O campo :attribute deve ser um array.',
            'rates.min'                                     => 'O campo :attribute deve ter pelo menos :min item(s).',
            'rates.*.product_type.required'                 => 'O campo :attribute é obrigatório.',
            'rates.*.product_type.enum'                     => 'O campo :attribute deve ser um valor válido.',
            'rates.*.brand.required'                        => 'O campo :attribute é obrigatório.',
            'rates.*.brand.string'                          => 'O campo :attribute deve ser uma string.',
            'rates.*.brand.max'                             => 'O campo :attribute não pode ter mais de :max caracteres.',
            'rates.*.installment_min.required'              => 'O campo :attribute é obrigatório.',
            'rates.*.installment_min.integer'               => 'O campo :attribute deve ser um número inteiro.',
            'rates.*.installment_min.min'                   => 'O campo :attribute deve ser no mínimo :min.',
            'rates.*.installment_max.required'              => 'O campo :attribute é obrigatório.',
            'rates.*.installment_max.integer'               => 'O campo :attribute deve ser um número inteiro.',
            'rates.*.installment_max.gte'                   => 'A parcela máxima deve ser maior ou igual à parcela mínima.',
            'rates.*.rate_percentage.required'              => 'O campo :attribute é obrigatório.',
            'rates.*.rate_percentage.numeric'               => 'O campo :attribute deve ser um número.',
            'rates.*.rate_percentage.min'                   => 'O campo :attribute deve ser no mínimo :min.',
            'rates.*.rate_fixed.required'                   => 'O campo :attribute é obrigatório.',
            'rates.*.rate_fixed.numeric'                    => 'O campo :attribute deve ser um número.',
            'rates.*.rate_fixed.min'                        => 'O campo :attribute deve ser no mínimo :min.',
            'rates.*.effective_date.required'               => 'O campo :attribute é obrigatório.',
            'rates.*.effective_date.date_format'            => 'O campo :attribute deve estar no formato de data Y-m-d.',
            'anticipation.array'                            => 'O campo :attribute deve ser um array.',
            'anticipation.anticipation_type.required_with'  => 'O campo :attribute é obrigatório quando o campo anticipation está presente.',
            'anticipation.anticipation_type.enum'           => 'O campo :attribute deve ser um valor válido.',
            'anticipation.rate_percentage_monthly.numeric'  => 'O campo :attribute deve ser um número.',
            'anticipation.rate_percentage_monthly.min'      => 'O campo :attribute deve ser no mínimo :min.',
            'anticipation.rate_fixed_per_operation.numeric' => 'O campo :attribute deve ser um número.',
            'anticipation.rate_fixed_per_operation.min'     => 'O campo :attribute deve ser no mínimo :min.',
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
