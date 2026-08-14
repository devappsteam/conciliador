<?php

namespace App\Modules\ERP\Contract\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'code' => $this->code,
            'company' => $this->whenLoaded('company', fn() => $this->company->only(['uuid', 'name'])),
            'status' => $this->status->value,
            'billing_cycle' => $this->billing_cycle->value,
            'amount' => $this->amount,
            'transaction_limit' => $this->transaction_limit,
            'overage_fee' => $this->overage_fee,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
