<?php

namespace App\Modules\ERP\Contract\Http\Requests;

use App\Modules\ERP\Contract\Enums\BillingCycleEnum;
use App\Modules\ERP\Contract\Enums\ContractStatusEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'            => ['sometimes', 'required', Rule::enum(ContractStatusEnum::class)],
            'billing_cycle'     => ['sometimes', 'required', Rule::enum(BillingCycleEnum::class)],
            'amount'            => ['sometimes', 'required', 'numeric', 'min:0'],
            'transaction_limit' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'overage_fee'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'start_date'        => ['sometimes', 'required', 'date'],
            'end_date'          => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'notes'             => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status'            => 'Status',
            'billing_cycle'     => 'Ciclo de cobrança',
            'amount'            => 'Valor da assinatura',
            'transaction_limit' => 'Limite de transações mensais conciliadas',
            'overage_fee'       => 'Taxa de excedente',
            'start_date'        => 'Data de início',
            'end_date'          => 'Data de término',
            'notes'             => 'Observações',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'           => 'O campo :attribute é obrigatório.',
            'status.enum'               => 'O campo :attribute deve ser um valor válido.',
            'billing_cycle.required'    => 'O campo :attribute é obrigatório.',
            'billing_cycle.enum'        => 'O campo :attribute deve ser um valor válido.',
            'amount.required'           => 'O campo :attribute é obrigatório.',
            'amount.numeric'            => 'O campo :attribute deve ser um número.',
            'amount.min'                => 'O campo :attribute deve ser maior ou igual a 0.',
            'transaction_limit.integer' => 'O campo :attribute deve ser um número inteiro.',
            'transaction_limit.min'     => 'O campo :attribute deve ser maior ou igual a 0.',
            'overage_fee.required'      => 'O campo :attribute é obrigatório.',
            'overage_fee.numeric'       => 'O campo :attribute deve ser um número.',
            'overage_fee.min'           => 'O campo :attribute deve ser maior ou igual a 0.',
            'start_date.required'       => 'O campo :attribute é obrigatório.',
            'start_date.date'           => 'O campo :attribute deve ser uma data válida.',
            'end_date.date'             => 'O campo :attribute deve ser uma data válida.',
            'end_date.after_or_equal'   => 'O campo :attribute deve ser uma data posterior ou igual à data de início.',
            'notes.string'              => 'O campo :attribute deve ser uma string.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if (!$this->expectsJson()) {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(
            response()->json([
                'message' => 'Falha na validação dos dados enviados.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
